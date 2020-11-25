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

use Madsoft\Library\Error;
use Madsoft\Library\Invoker;
use Madsoft\Library\Logger;
use Madsoft\Library\Tester\Test;
use function count;

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
class ErrorTest extends Test
{
    protected Logger $logger;
    protected Invoker $invoker;

    /**
     * Method __construct
     *
     * @param Logger  $logger  logger
     * @param Invoker $invoker invoker
     */
    public function __construct(Logger $logger, Invoker $invoker)
    {
        $this->logger = $logger;
        $this->invoker = $invoker;
    }
    
    /**
     * Method testError
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testError(): void
    {
        $this->logger->setCollect(true);
        $response = $this->get('q=non-exists-request');
        $collection = $this->logger->setCollect(false)->getCollection();
        $this->assertEquals(1, count($collection));
        $this->assertStringContains(
            "Message: 'Route not found for requested area: 'public', "
                . "method: 'GET', query: 'q=non-exists-request''",
            $collection[0]
        );
        $this->assertStringContains('Error', $response);
        $this->assertStringContains('Something went wrong', $response);
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
        $error = $this->invoker->getInstance(Error::class);
        $result = $error->error();
        $this->assertTrue((bool)$result);
    }
}
