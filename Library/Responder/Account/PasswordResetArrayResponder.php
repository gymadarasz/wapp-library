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
use Madsoft\Library\Crud;
use Madsoft\Library\Mailer;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\ArrayResponder;
use Madsoft\Library\Template;
use Madsoft\Library\Token;

/**
 * PasswordResetArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class PasswordResetArrayResponder extends ArrayResponder
{
    const EMAIL_TPL_PATH = __DIR__ . '/Template/phtml/';
    
    protected Template $template;
    protected Token $token;
    protected Crud $crud;
    protected AccountValidator $validator;
    protected Mailer $mailer;

    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Template         $template  template
     * @param Token            $token     token
     * @param Crud             $crud      crud
     * @param AccountValidator $validator validator
     * @param Mailer           $mailer    mailer
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Template $template,
        Token $token,
        Crud $crud,
        AccountValidator $validator,
        Mailer $mailer
    ) {
        parent::__construct($messages, $merger);
        $this->template = $template;
        $this->token = $token;
        $this->crud = $crud;
        $this->validator = $validator;
        $this->mailer = $mailer;
    }
    /**
     * Method getPasswordResetFormResponse
     *
     * @param Assoc $params params
     *
     * @return mixed[]
     */
    public function getPasswordResetFormResponse(Assoc $params): array
    {
        $token = $params->get('token', '');
        if ($token) {
            $user = $this->crud->getRow('user', ['id'], ['token' => $token]);
            if (!($user['id'] ?? '')) {
                return $this->getErrorResponse(
                    'Invalid token'
                );
            }
            return $this->getResponse(
                ['token' => $token]
            );
        }
        return $this->getResponse();
    }

    /**
     * Method getPasswordResetRequestResponse
     *
     * @param Assoc $params params
     *
     * @return mixed[]
     */
    public function getPasswordResetRequestResponse(Assoc $params): array
    {
        $errors = $this->validator->validateReset($params);
        if ($errors) {
            return $this->getErrorResponse(
                'Reset password request failed',
                $errors
            );
        }
        
        $email = (string)$params->get('email');
        $user = $this->crud->getRow('user', ['email'], ['email' => $email]);
        if (($user['email'] ?? '') !== $email) {
            return $this->getErrorResponse(
                'Email address not found'
            );
        }
        
        $token = $this->token->generate();
        if (!$this->crud->setRow('user', ['token' => $token], ['email' => $email])) {
            return $this->getErrorResponse(
                'Token is not updated'
            );
        }
        
        if (!$this->sendResetEmail($email, $token)) {
            return $this->getErrorResponse(
                'Email sending failed'
            );
        }
        
        return $this->getSuccessResponse(
            'Password reset request email sent'
        );
    }
    
    /**
     * Method sendResetEmail
     *
     * @param string $email email
     * @param string $token token
     *
     * @return bool
     */
    protected function sendResetEmail(string $email, string $token): bool
    {
        $message = $this->template->process(
            'emails/reset.phtml',
            [
            //                'base' => $this->config->get('Site')->get('base'),
                'token' => $token,
            ],
            $this::EMAIL_TPL_PATH
        );
        return $this->mailer->send(
            $email,
            'Pasword reset requested',
            $message
        );
    }
}
