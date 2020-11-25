<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Test
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

use Madsoft\Library\Invoker;
use Madsoft\Library\Test\LibraryTestCleaner;
use Madsoft\Library\Tester\Tester;

require __DIR__ . '/../../../vendor/autoload.php';

if (php_sapi_name() !== 'cli') {
    throw new RuntimeException('Test can run only from command line.');
}

$tester = (new Invoker())->getInstance(Tester::class)->setCleaners(
    [
        //    TalkbotTestCleaner::class,
        LibraryTestCleaner::class,
    ]
);
        
$tester->cleanUp();

$tester->getCoverage()->start(
    [
        __DIR__ . "/../../../vendor/",
        __DIR__ . "/../../../lib/Library/Coverage/Coverage.php",
        __DIR__ . "/../../../lib/Library/Tester/Test.php",
        __DIR__ . "/../../../lib/Library/Tester/Tester.php",
        __DIR__ . "/../../../lib/tests/",
        __DIR__ . "/../../../src/tests/",
    ]
);

array_shift($argv);
if (empty($argv)) {
    $tester->test();
} else {
    foreach ($argv as $arg) {
        $tester->runTestFile($arg, '');
    }
}

$tester->cleanUp();

exit($tester->stat() ? 0 : 1);
