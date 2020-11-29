<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Account;

use Madsoft\Library\Config;
use Madsoft\Library\Crud;
use Madsoft\Library\Encrypter;
use Madsoft\Library\Invoker;
use Madsoft\Library\Mailer;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Responder\Account\AccountValidator;
use Madsoft\Library\Responder\Account\PasswordResetArrayResponder;
use Madsoft\Library\Responder\Account\Template\PasswordResetTemplateResponder;
use Madsoft\Library\Template;
use Madsoft\Library\Tester\Test;
use Madsoft\Library\Token;

/**
 * ResetTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @suppress PhanUnreferencedClass
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ResetTest extends Test
{
    /**
     * Method testDoResetDbFails
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanPossiblyUndeclaredMethod
     * @suppress PhanTypeMismatchArgumentProbablyReal
     */
    public function testDoResetDbFails(Invoker $invoker): void
    {
        $template = $invoker->getInstance(Template::class);
        $merger = $invoker->getInstance(Merger::class);
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('getRow')->andReturn(
        //            (new Row)->setFields(['email' => 'emailaddr1'])
            ['email' => 'emailaddr1']
        );
        $crud->shouldReceive('setRow')->andReturnFalse();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = 'emailaddr1';
        $params = $invoker->getInstance(Params::class);
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateReset')->andReturn([]);
        $mailer = $invoker->getInstance(Mailer::class);
        $config = $invoker->getInstance(Config::class);
        $messages = new Messages();
        $encrypter = new Encrypter();
        $token = new Token($encrypter);
        $reset = new PasswordResetTemplateResponder(
            $messages,
            $merger,
            $template,
        );
        $arrayResponder = new PasswordResetArrayResponder(
            $messages,
            $merger,
            $template,
            $token,
            $crud, // @phpstan-ignore-line
            $validator, // @phpstan-ignore-line
            $mailer,
            $config
        );
        $result = $reset->getPasswordResetRequestResponse($arrayResponder, $params);
        $this->assertStringContains('Token is not updated', $result);
    }
    
    /**
     * Method testDoResetMailFails
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanPossiblyUndeclaredMethod
     * @suppress PhanTypeMismatchArgumentProbablyReal
     */
    public function testDoResetMailFails(Invoker $invoker): void
    {
        $template = $invoker->getInstance(Template::class);
        $merger = $invoker->getInstance(Merger::class);
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('getRow')->andReturn(
        //            (new Row)->setFields(['email' => 'emailaddr1'])
            ['email' => 'emailaddr1']
        );
        $crud->shouldReceive('setRow')->andReturnTrue();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = 'emailaddr1';
        $params = $invoker->getInstance(Params::class);
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateReset')->andReturn([]);
        $mailer = $this->getMock(Mailer::class);
        $mailer->shouldReceive('send')->andReturnFalse();
        $config = $invoker->getInstance(Config::class);
        $messages = new Messages();
        $encrypter = new Encrypter();
        $token = new Token($encrypter);
        $reset = new PasswordResetTemplateResponder(
            $messages,
            $merger,
            $template,
        );
        $arrayResponder = new PasswordResetArrayResponder(
            $messages,
            $merger,
            $template,
            $token,
            $crud, // @phpstan-ignore-line
            $validator, // @phpstan-ignore-line
            $mailer, // @phpstan-ignore-line
            $config
        );
        $result = $reset->getPasswordResetRequestResponse($arrayResponder, $params);
        $this->assertStringContains('Email sending failed', $result);
    }
}
