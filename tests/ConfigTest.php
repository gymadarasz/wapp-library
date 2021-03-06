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
use Madsoft\Library\Merger;
use Madsoft\Library\Tester\Test;
use RuntimeException;

/**
 * ConfigTest
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
class ConfigTest extends Test
{
    /**
     * Method testReadConfigFails
     *
     * @param Invoker $invoker invoker
     * @param Merger  $merger  merger
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanNoopNew
     */
    public function testReadConfigFails(Invoker $invoker, Merger $merger): void
    {
        $exception = null;
        try {
            new ConfigFileNotFoundMock(new Invoker(), $merger);
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        $this->assertNotEquals(null, $exception);
        
        
        $errlvl = error_reporting();
        error_reporting(0);
        $exception = null;
        try {
            new ConfigInvalidMock($invoker, $merger);
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        error_reporting($errlvl);
        $this->assertNotEquals(null, $exception);
    }
}
