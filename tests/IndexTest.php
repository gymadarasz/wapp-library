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
use Madsoft\Library\Responder\Example\Example;
use Madsoft\Library\Tester\Test;

/**
 * IndexTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class IndexTest extends Test
{
    /**
     * Method testIndex
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testIndex(): void
    {
        $response = $this->get('q=example');
        $this->assertStringContains('Index', $response);
    }
    
    /**
     * Method testMethods
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testMethods(): void
    {
        $invoker = new Invoker();
        $index = $invoker->getInstance(Example::class);
        $result = $index->getPublic();
        $this->assertTrue((bool)$result);
    }
}
