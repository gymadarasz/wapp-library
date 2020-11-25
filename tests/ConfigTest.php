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

use Madsoft\Library\Merger;
use Madsoft\Library\Template;
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
     * @param Template $template template
     * @param Merger   $merger   merger
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     * @suppress PhanNoopNew
     */
    public function testReadConfigFails(Template $template, Merger $merger): void
    {
        $exception = null;
        try {
            new ConfigFileNotFoundMock($template, $merger);
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        $this->assertNotEquals(null, $exception);
        
        
        $errlvl = error_reporting();
        error_reporting(0);
        $exception = null;
        try {
            new ConfigInvalidMock($template, $merger);
            $this->assertTrue(false);
        } catch (RuntimeException $exception) {
            $this->assertTrue(true);
        }
        error_reporting($errlvl);
        $this->assertNotEquals(null, $exception);
    }
}
