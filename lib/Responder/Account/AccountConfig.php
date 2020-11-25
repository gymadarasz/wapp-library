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
                    LoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'login' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginFormResponse'
                ],
                'registry' =>
                [
                    RegistryTemplateResponder::class,
                    'getRegistryFormResponse'
                ],
                'resend' =>
                [
                    RegistryTemplateResponder::class,
                    'getResendResponse'
                ],
                'activate' =>
                [
                    ActivateTemplateResponder::class,
                    'getActivateResponse'
                ],
                'reset' =>
                [
                    PasswordResetTemplateResponder::class,
                    'getPasswordResetFormResponse'
                ],
            ],
            'POST' => [
                '' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'login' =>
                [
                    LoginTemplateResponder::class,
                    'getLoginResponse'
                ],
                'registry' =>
                [
                    RegistryTemplateResponder::class,
                    'getRegistryResponse'
                ],
                'reset' =>
                [
                    PasswordResetTemplateResponder::class,
                    'getPasswordResetRequestResponse'
                ],
                'change' =>
                [
                    PasswordChangeTemplateResponder::class,
                    'getPasswordChangeResponse'
                ],
            ],
        ],
        'protected' => [
            'GET' => [
                'logout' =>
                [
                    LogoutTemplateResponder::class,
                    'getLogoutResponse'
                ],
            ],
        ],
    ];
}
