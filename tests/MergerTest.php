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
use Madsoft\Library\Tester\Test;

/**
 * MergerTest
 *
 * @category  PHP
 * @package   Madsoft\Library\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class MergerTest extends Test
{
    /**
     * Method testMerge
     *
     * @param Merger $merger merger
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function testMerge(Merger $merger): void
    {
        $results = $merger->merge([[['a']]], [[['a']]]);
        $this->assertEquals([[['a']]], $results);
        $results = $merger->merge([[['a', 'b']]], [[['b', 'c']]]);
        $this->assertEquals([[['a', 'b', 'c']]], $results);
        $results = $merger->merge([[['a', 'b']]], [[['b', ['c']]]]);
        $this->assertEquals([[['a', 'b', ['c']]]], $results);
    }
}
