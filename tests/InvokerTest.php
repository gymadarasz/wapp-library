<?php declare(strict_types=1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test;

use Madsoft\Library\Invoker;
use Madsoft\Library\Tester\Test;
use Madsoft\Library\Test\Injectable;
use Madsoft\Library\Test\NotInjectable;
use RuntimeException;

/**
 * InvokerTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @suppress PhanUnreferencedClass
 */
class InvokerTest extends Test
{
    /**
     * Method testGetInstance
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testGetInstance(Invoker $invoker): void
    {
        $invoker = $invoker->getInstance(Invoker::class);
        $this->assertEquals($invoker, $invoker);

        $newInvoker = new Invoker();
        $this->assertNotEquals($invoker, $newInvoker);

        $msg = '';
        try {
            $invoker->getInstance('ThisClassNotExists');
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertEquals('Class not exists: "ThisClassNotExists"', $msg);

        try {
            $invoker->getInstance(NotInjectable::class);
        } catch (RuntimeException $exception) {
            $msg = $exception->getMessage();
        }
        $this->assertStringContains(
            sprintf(
                Invoker::ERR_METHOD_HAS_NONCLASS_ARG,
                NotInjectable::class,
                '__construct'
            ),
            $msg
        );
        $invoker->getInstance(Injectable::class);

        try {
            $invoker->invoke(['class' => Injectable::class]);
            $this->assertTrue(false);
        } catch (RuntimeException $exc) {
            $this->assertEquals(
                'Missing route controller: Madsoft\Library\Test\Injectable::???',
                $exc->getMessage()
            );
        }

        $invokerMock = new InvokerMock();
        $invokerMock->setInstances([1, 2, 3]);
        try {
            $invokerMock->free();
            $this->assertTrue(false);
        } catch (RuntimeException $exc) {
            $this->assertEquals(
                'Class name should be a string, integer found.',
                $exc->getMessage()
            );
        }
    }
}
