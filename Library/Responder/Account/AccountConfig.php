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

use Madsoft\Library\Responder\Account\Template\ActivateTemplateResponder;
use Madsoft\Library\Responder\Account\Template\LoginTemplateResponder;
use Madsoft\Library\Responder\Account\Template\LogoutTemplateResponder;
use Madsoft\Library\Responder\Account\Template\PasswordChangeTemplateResponder;
use Madsoft\Library\Responder\Account\Template\PasswordResetTemplateResponder;
use Madsoft\Library\Responder\Account\Template\RegistryTemplateResponder;

/**
 * AccountConfig
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class AccountConfig
{
    const ROUTES = [
        'public' => [
            'GET' => [
                '' =>
                [
                    'class' => LoginTemplateResponder::class,
                    'method' => 'getLoginFormResponse'
                ],
                'login' =>
                [
                    'class' => LoginTemplateResponder::class,
                    'method' => 'getLoginFormResponse'
                ],
                'registry' =>
                [
                    'class' => RegistryTemplateResponder::class,
                    'method' => 'getRegistryFormResponse'
                ],
                'resend' =>
                [
                    'class' => RegistryTemplateResponder::class,
                    'method' => 'getResendResponse'
                ],
                'activate' =>
                [
                    'class' => ActivateTemplateResponder::class,
                    'method' => 'getActivateResponse'
                ],
                'reset' =>
                [
                    'class' => PasswordResetTemplateResponder::class,
                    'method' => 'getPasswordResetFormResponse'
                ],
            ],
            'POST' => [
                '' =>
                [
                    'class' => LoginTemplateResponder::class,
                    'method' => 'getLoginResponse'
                ],
                'login' =>
                [
                    'class' => LoginTemplateResponder::class,
                    'method' => 'getLoginResponse'
                ],
                'registry' =>
                [
                    'class' => RegistryTemplateResponder::class,
                    'method' => 'getRegistryResponse'
                ],
                'reset' =>
                [
                    'class' => PasswordResetTemplateResponder::class,
                    'method' => 'getPasswordResetRequestResponse'
                ],
                'change' =>
                [
                    'class' => PasswordChangeTemplateResponder::class,
                    'method' => 'getPasswordChangeResponse'
                ],
            ],
        ],
        'protected' => [
            'GET' => [
                'logout' =>
                [
                    'class' => LogoutTemplateResponder::class,
                    'method' => 'getLogoutResponse'
                ],
            ],
        ],
    ];
}
