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

use Madsoft\Library\Json;
use Madsoft\Library\Tester\Test;

/**
 * JsonTest
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
class JsonTest extends Test
{
    /**
     * Method testJson
     *
     * @param Json $json json
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testJson(Json $json): void
    {
        $data = ['test' => [1, 2, 'foo', 'bar'], 'foo' => 'bazz'];
        $encoded = $json->encode($data);
        $this->assertEquals('{"test":[1,2,"foo","bar"],"foo":"bazz"}', $encoded);
        $decoded = (array)$json->decode($encoded);
        $this->assertEquals($data, $decoded);
    }
}
