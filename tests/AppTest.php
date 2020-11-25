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

use Madsoft\Library\App;
use Madsoft\Library\Invoker;
use Madsoft\Library\Responder\Account\AccountConfig;
use Madsoft\Library\Tester\Test;

/**
 * AppTest
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
class AppTest extends Test
{
    /**
     * Method testApp
     *
     * @param Invoker $invoker invoker
     *
     * @return void
     *
     * @suppressWarnings(PHPMD.Superglobals);
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testApp(Invoker $invoker): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $output = (new App($invoker))->getOutput([AccountConfig::ROUTES]);
        $this->assertTrue((bool)$output);
    }
}
