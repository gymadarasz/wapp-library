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
use Madsoft\Library\Encrypter;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\ArrayResponder;

/**
 * PasswordChangeArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class PasswordChangeArrayResponder extends ArrayResponder
{
    protected Crud $crud;
    protected AccountValidator $validator;
    protected Encrypter $encrypter;
    
    /**
     * Method __construct
     *
     * @param Messages         $messages  messages
     * @param Merger           $merger    merger
     * @param Crud             $crud      crud
     * @param AccountValidator $validator validator
     * @param Encrypter        $encrypter encrypter
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Crud $crud,
        AccountValidator $validator,
        Encrypter $encrypter
    ) {
        parent::__construct($messages, $merger);
        $this->crud = $crud;
        $this->validator = $validator;
        $this->encrypter = $encrypter;
    }
    
    /**
     * Method getPasswordChangeResponse
     *
     * @param Assoc $params params
     *
     * @return mixed[]
     */
    public function getPasswordChangeResponse(Assoc $params): array
    {
        $errors = $this->validator->validateChangePassword($params);
        if ($errors) {
            return $this->getErrorResponse(
                'Password change failed',
                $errors,
                [
                    'token' => $params->get('token')
                ]
            );
        }
        
        if (!$this->crud->setRow(
            'user',
            [
                'hash' => $this->encrypter->encrypt($params->get('password')),
                'token' => '',
            ],
            [
                'token' => $params->get('token'),
            ]
        )
        ) {
            return $this->getErrorResponse(
                'Password is not saved',
                [],
                [
                    'token' => $params->get('token')
                ]
            );
        }
        
        return $this->getSuccessResponse(
            'Password is changed'
        );
    }
}
