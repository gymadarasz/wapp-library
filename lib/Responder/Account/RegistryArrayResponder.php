<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Responder\Account;

use Madsoft\Library\Assoc;
use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Encrypter;
use Madsoft\Library\Mailer;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\ArrayResponder;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Token;

/**
 * RegistryArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class RegistryArrayResponder extends ArrayResponder
{
    const EMAIL_TPL_PATH = __DIR__ . '/Template/phtml/';
    
    protected Template $template;
    protected Token $token;
    protected Encrypter $encrypter;
    protected Crud $crud;
    protected AccountValidator $validator;
    protected Mailer $mailer;
    protected Config $config;
    
    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Template         $template  template
     * @param Token            $token     token
     * @param Encrypter        $encrypter encrypter
     * @param Crud             $crud      crud
     * @param AccountValidator $validator validator
     * @param Mailer           $mailer    mailer
     * @param Config           $config    config
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Template $template,
        Token $token,
        Encrypter $encrypter,
        Crud $crud,
        AccountValidator $validator,
        Mailer $mailer,
        Config $config
    ) {
        parent::__construct($messages, $merger);
        $this->template = $template;
        $this->token = $token;
        $this->encrypter = $encrypter;
        $this->crud = $crud;
        $this->validator = $validator;
        $this->mailer = $mailer;
        $this->config = $config;
    }

    /**
     * Method getRegistryResponse
     *
     * @param Assoc   $params  params
     * @param Session $session session
     *
     * @return mixed[]
     */
    public function getRegistryResponse(Assoc $params, Session $session): array
    {
        $errors = $this->validator->validateRegistry($params);
        if ($errors) {
            return $this->getErrorResponse(
                'Invalid registration data',
                $errors
            );
        }
        
        $email = $params->get('email');
        $token = $this->token->generate();
        
        $user = $this->crud->get('user', ['email'], ['email' => $email], 1, 0, -1);
        if ($user->get('email') === $email) {
            return $this->getErrorResponse(
                'Email address already registered',
                $errors
            );
        }
        
        if (!$this->crud->add(
            'user',
            [
                'email' => $email,
                'hash' => $this->encrypter->encrypt($params->get('password')),
                'token' => $token,
                'active' => '0',
            ],
            -1
        )
        ) {
            return $this->getErrorResponse(
                'User is not saved'
            );
        }
        $session->set('resend', ['email' => $email, 'token' => $token]);
        
        if (!$this->sendActivationEmail($email, $token)) {
            return $this->getWarningResponse(
                'Activation email is not sent',
                $user->getFields()
            );
        }
        
        return $this->getSuccessResponse(
            'We sent an activation email to your email account, '
                . 'please follow the instructions.'
        );
    }

    /**
     * Method getResendResponse
     *
     * @param Session $session session
     *
     * @return mixed[]
     */
    public function getResendResponse(Session $session): array
    {
        $resend = $session->get('resend');
        $email = $resend['email'];
        $token = $resend['token'];
        if (!$this->sendActivationEmail($email, $token)) {
            return $this->getErrorResponse(
                'Activation email is not sent'
            );
        }
        
        return $this->getSuccessResponse(
            'We re-sent an activation email to your email account, '
                . 'please follow the instructions.'
        );
    }

    /**
     * Method sendActivationEmail
     *
     * @param string $email email
     * @param string $token token
     *
     * @return bool
     */
    protected function sendActivationEmail(string $email, string $token): bool
    {
        $message = $this->template->process(
            'emails/activation.phtml',
            [
                'base' => $this->config->get('Site')->get('base'),
                'token' => $token,
            ],
            $this::EMAIL_TPL_PATH
        );
        return $this->mailer->send(
            $email,
            'Activate your account',
            $message
        );
    }
}
