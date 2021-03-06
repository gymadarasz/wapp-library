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
use Madsoft\Library\Csrf;
use Madsoft\Library\Encrypter;
use Madsoft\Library\Invoker;
use Madsoft\Library\Mailer;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Responder\Account\AccountValidator;
use Madsoft\Library\Responder\Account\RegistryArrayResponder;
use Madsoft\Library\Responder\Account\Template\RegistryTemplateResponder;
use Madsoft\Library\Safer;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Tester\Test;
use Madsoft\Library\Token;

/**
 * RegistryTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @suppress PhanUnreferencedClass
 */
class RegistryTest extends Test
{
    /**
     * Method testDoRegistryDbFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     * @suppress PhanPossiblyUndeclaredMethod
     */
    public function testDoRegistryDbFails(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = 'an-email';
        $_GET['password'] = 'a-password';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $invoker = new Invoker();
        
        $merger = new Merger();
        
        $config = new Config($invoker, $merger);
        
        $template = new Template($config, $safer, $csrf);
        
        //        $user = $this->getMock(Row::class);
        //        $user->shouldReceive('get')->andReturnUsing(
        //            static function ($arg) {
        //                if ($arg === 'email') {
        //                    return 'an-email-2';
        //                }
        //                throw new RuntimeException('Invalid argument: ' . $arg);
        //            }
        //        );
        
        $user = ['email' => 'an-email-2'];
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('getRow')->andReturn($user);
        $crud->shouldReceive('add')->andReturnFalse();
        
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateRegistry')->andReturn([]);
        
        $mailer = $this->getMock(Mailer::class);
        
        $messages = new Messages();
        
        $registy = new RegistryTemplateResponder(
            $messages,
            $merger,
            $template
        );
        $encrypter = new Encrypter();
        $token = new Token($encrypter);
        $arrayResponder = new RegistryArrayResponder(
            $messages,
            $merger,
            $template,
            $token,
            $encrypter,
            $crud, // @phpstan-ignore-line
            $validator, // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            $mailer // @phpstan-ignore-line
        );
        $result = $registy->getRegistryResponse($arrayResponder, $params, $session);
        $this->assertStringContains('User is not saved', $result);
    }
    
    /**
     * Method testDoRegistryEmailFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     * @suppress PhanPossiblyUndeclaredMethod
     */
    public function testDoRegistryEmailFails(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['email'] = 'an-email';
        $_GET['password'] = 'a-password';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $invoker = new Invoker();
        
        $merger = new Merger();
        
        $config = new Config($invoker, $merger);
        
        $template = new Template($config, $safer, $csrf);
        
        //        $user = $this->getMock(Row::class);
        //        $user->shouldReceive('get')->andReturnUsing(
        //            static function ($arg) {
        //                if ($arg === 'email') {
        //                    return 'an-email-2';
        //                }
        //                throw new RuntimeException('Invalid argument: ' . $arg);
        //            }
        //        );
        //        $user->shouldReceive('getFields')
        //          ->andReturn(['email' => 'an-email-2']);
        
        $user = ['email' => 'an-email-2'];
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('getRow')->andReturn($user);
        $crud->shouldReceive('add')->andReturnTrue();
        
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateRegistry')->andReturn([]);
        
        $mailer = $this->getMock(Mailer::class);
        $mailer->shouldReceive('send')->andReturnFalse();
        
        $messages = new Messages();
        
        $registy = new RegistryTemplateResponder(
            $messages,
            $merger,
            $template
        );
        $encrypter = new Encrypter();
        $token = new Token($encrypter);
        $arrayResponder = new RegistryArrayResponder(
            $messages,
            $merger,
            $template,
            $token,
            $encrypter,
            $crud, // @phpstan-ignore-line
            $validator, // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            $mailer // @phpstan-ignore-line
        );
        $result = $registy->getRegistryResponse($arrayResponder, $params, $session);
        $this->assertStringContains('Activation email is not sent', $result);
    }
}
