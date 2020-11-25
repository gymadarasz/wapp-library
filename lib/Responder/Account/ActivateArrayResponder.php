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
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\ArrayResponder;
use Madsoft\Library\Session;

/**
 * ActivateArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ActivateArrayResponder extends ArrayResponder
{
    protected Crud $crud;
    protected AccountValidator $validator;
    
    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Crud             $crud      crud
     * @param AccountValidator $validator validator
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Crud $crud,
        AccountValidator $validator
    ) {
        parent::__construct($messages, $merger);
        $this->crud = $crud;
        $this->validator = $validator;
    }
    
    /**
     * Method getActivateResponse
     *
     * @param Assoc   $params  params
     * @param Session $session session
     *
     * @return mixed[]
     */
    public function getActivateResponse(Assoc $params, Session $session): array
    {
        $errors = $this->validator->validateActivate($params);
        if ($errors) {
            return $this->getErrorResponse(
                'Account activation failed',
                $errors
            );
        }
        
        $token = $params->get('token');
        
        $user = $this->crud->get(
            'user',
            ['id', 'active'],
            ['token' => $token],
            1,
            0,
            -1
        );
        if (!$user->get('id')) {
            return $this->getErrorResponse(
                'Invalid token'
            );
        }
        
        if ($user->get('active')) {
            return $this->getErrorResponse(
                'User is active already'
            );
        }
        
        if (!$this->crud->set('user', ['active' => '1'], ['token' => $token])) {
            return $this->getErrorResponse(
                'User activation failed'
            );
        }
        
        $session->unset('resend');
        
        return $this->getSuccessResponse(
            'Account is now activated'
        );
    }
}
