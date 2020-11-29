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

use Madsoft\Library\Crud;
use Madsoft\Library\Csrf;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Params;
use Madsoft\Library\Responder\Account\AccountValidator;
use Madsoft\Library\Responder\Account\ActivateArrayResponder;
use Madsoft\Library\Responder\Account\Template\ActivateTemplateResponder;
use Madsoft\Library\Safer;
use Madsoft\Library\Session;
use Madsoft\Library\Template;
use Madsoft\Library\Tester\Test;

/**
 * ActivateTest
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
class ActivateTest extends Test
{
    
    /**
     * Method testDoActivateDbFails
     *
     * @return void
     *
     * @suppress PhanUndeclaredClassMethod
     * @suppress PhanTypeMismatchArgument
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanUnreferencedClosure
     * @suppress PhanPossiblyUndeclaredMethod
     */
    public function testDoActivateDbFails(): void
    {
        //        $user = $this->getMock(Row::class);
        //        $user->shouldReceive('get')->andReturnUsing(
        //            static function ($arg) {
        //                if ($arg === 'id') {
        //                    return 123;
        //                }
        //                if ($arg === 'active') {
        //                    return 0;
        //                }
        //                throw new RuntimeException('Invalid argument: ' . $arg);
        //            }
        //        );
        $user = [
            'id' => 123,
            'active' => 0,
        ];
        
        $crud = $this->getMock(Crud::class);
        $crud->shouldReceive('getRow')->andReturn($user);
        $crud->shouldReceive('setRow')->andReturnFalse();
        
        $validator = $this->getMock(AccountValidator::class);
        $validator->shouldReceive('validateActivate')->andReturn([]);
        
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['token'] = 'a-token';
        $params = new Params();
        
        $session = new Session();
        
        $csrf = new Csrf($session, $params);
        
        $safer = new Safer();
        
        $template = new Template($safer, $csrf);
        
        $merger = new Merger();
        
        $messages = new Messages();
        
        $arrayResponder = new ActivateArrayResponder(
            $messages,
            $merger,
            $crud, // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            $validator
        );
        
        $activate = new ActivateTemplateResponder(
            $messages,
            $merger,
            $template
        );
        $result = $activate->getActivateResponse($arrayResponder, $params, $session);
        $this->assertStringContains('User activation failed', $result);
    }
}
