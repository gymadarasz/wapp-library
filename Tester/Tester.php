<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Tester
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Tester;

use Exception;
use Madsoft\Library\Coverage\Coverage;
use Madsoft\Library\Folders;
use Madsoft\Library\Invoker;
use Madsoft\Library\Logger;
use RuntimeException;

/**
 * Tester
 *
 * @category  PHP
 * @package   Madsoft\Library\Tester
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Tester extends Test
{
    const TESTS_PATH = __DIR__ . '/../../tests';
    
    /**
     * Variable $cleaners
     *
     * @var string[]
     */
    protected array $cleaners = [];
    
    protected Folders $folders;
    protected Invoker $invoker;
    protected Coverage $coverage;

    /**
     * Method __construct
     *
     * @param Folders  $folders  folders
     * @param Invoker  $invoker  invoker
     * @param Coverage $coverage coverage
     */
    public function __construct(
        Folders $folders,
        Invoker $invoker,
        Coverage $coverage
    ) {
        $this->folders = $folders;
        $this->invoker = $invoker;
        $this->coverage = $coverage;
    }
    
    /**
     * Method setCleaners
     *
     * @param string[] $cleaners cleaners
     *
     * @return self
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function setCleaners(array $cleaners): self
    {
        $this->cleaners = $cleaners;
        return $this;
    }
    
    /**
     * Method cleanUp
     *
     * @return void
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function cleanUp(): void
    {
        foreach ($this->cleaners as $cleaner) {
            $this->invoker->getInstance($cleaner)->cleanUp();
        }
    }
    
    /**
     * Method getCoverage
     *
     * @return Coverage
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getCoverage(): Coverage
    {
        return $this->coverage;
    }
    
    /**
     * Method test
     *
     * @param string $path path
     *
     * @return void
     */
    public function test(string $path = self::TESTS_PATH): void
    {
        $files = $this->folders->getFilesRecursive($path);
        foreach ($files as $file) {
            $this->runTestFile($file->getFilename(), $file->getPath());
        }
    }
    
    /**
     * Method runTestFile
     *
     * @param string $filename filename
     * @param string $path     path
     *
     * @return void
     */
    public function runTestFile(
        string $filename,
        string $path = self::TESTS_PATH
    ): void {
        $matches = [];
        if (preg_match(
            '/([a-zA-Z0-9][a-zA-Z0-9_]*Test).php$/',
            $filename,
            $matches
        )
        ) {
            $class = $matches[1];

            $fullname = $path . '/' . $filename;
            $namespace = $this->getPhpNamespace($fullname);

            $fullclass = "$namespace\\$class";

            include_once $fullname;
            $this->run($fullclass);
        }
    }
    
    /**
     * Method getPhpNamespace
     *
     * @param string $fullname fullname
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getPhpNamespace(string $fullname): string
    {
        $contents = file_get_contents($fullname);
        if (false === $contents) {
            throw new RuntimeException(
                'Unable to read test file: ' . $fullname
            );
        }
        $matches = [];
        if (preg_match('/namespace\s+(.+);/', $contents, $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    /**
     * Method run
     *
     * @param string $class class
     *
     * @return void
     * @throws RuntimeException
     */
    protected function run(string $class): void
    {
        $methods = get_class_methods($class);
        $test = $this->getTest($class);
        if (method_exists($test, 'beforeAll')) {
            $this->invoker->invoke([$class, 'beforeAll']);
        }
        foreach ($methods as $method) {
            if (preg_match('/^test/', $method)) {
                try {
                    if (method_exists($test, 'before')) {
                        $this->invoker->invoke([$class, 'before']);
                    }
                    $this->invoker->invoke([$class, $method]);
                    if (method_exists($test, 'after')) {
                        $this->invoker->invoke([$class, 'after']);
                    }
                } catch (Exception $exception) {
                    $test->assertFalse(
                        true,
                        "Tests should not throws exception but it's happened at "
                            . "$class::$method(), exception details:\n"
                            . (new Logger())->exceptionToString($exception)
                    );
                }
                if (!$test->asserts) {
                    throw new RuntimeException(
                        "$class::$method() has not any assertation."
                    );
                }
                $this->failInfos = array_merge($test->failInfos, $this->failInfos);
                $this->asserts += $test->asserts;
                $this->success += $test->success;
                $this->fails += $test->fails;
            }
        }
        if (method_exists($test, 'afterAll')) {
            $this->invoker->invoke([$class, 'afterAll']);
        }
        $this->invoker->free($class);
    }
    
    /**
     * Method getTest
     *
     * @param string $class class
     *
     * @return Test
     */
    protected function getTest(string $class): Test
    {
        return $this->invoker->getInstance($class);
    }
    
    /**
     * Method stat
     *
     * @param string $coverageOutput    coverageOutput
     * @param float  $coverageThreshold coverageThreshold
     *
     * @return bool
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function stat(
        string $coverageOutput = __DIR__ . '/coverage/coverage.html',
        float $coverageThreshold = 100.0
    ): bool {
        $coverageData = [];
        $coveragePassed = true;
        if ($this->coverage->isStarted()) {
            $coverageData = $this->coverage->getCoverageData();
            $coveragePercentage = $this->coverage->getPercentage($coverageData);
            $coveragePassed = $coverageThreshold <= $coveragePercentage;
            echo "\nCode coverage:\t$coveragePercentage %";
        }
        echo "\nAsserts:\t{$this->asserts}";
        echo "\nSuccess:\t{$this->success}";
        if ($this->fails) {
            echo "\nFails:\t{$this->fails}";
            echo "\n\n" . implode("\n\n", $this->failInfos);
            echo "\n";
            return false;
        }
        if (!$coveragePassed) {
            echo "\nCoverage thrashold ($coverageThreshold %) failed.";
            echo "\nGenerate coverage info to '$coverageOutput'...";
            $output = $this->coverage->generateCoverageInfo($coverageData);
            $writeOk = false === file_put_contents($coverageOutput, $output);
            if ($writeOk) {
                echo '[ERR]';
            }
            if (!$writeOk) {
                echo "[OK]";
            }
            echo "\n";
            return false;
        }
        echo "\n";
        return true;
    }
}
