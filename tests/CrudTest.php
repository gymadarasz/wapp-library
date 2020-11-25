<?php declare(strict_types = 1);

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
use RuntimeException;

/**
 * CrudTest
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
class CrudTest extends Test
{
    /**
     * Method testCrudInvalidLogicFails
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testCrudInvalidLogicFails(Invoker $invoker): void
    {
        $exc = null;
        $crud = $invoker->getInstance(CrudMock::class);
        try {
            $crud->getWherePublic('atable', [], 'NOT VALID LOGIC');
            $this->assertTrue(false);
        } catch (RuntimeException $exc) {
            $this->assertTrue(true);
        }
        $this->assertNotEquals(null, $exc);
    }
}
