<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library;

use RuntimeException;

/**
 * App
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class App
{
    const ROUTE_CACHE_FILE = __DIR__ . '/../route.cache.php';
    
    protected Invoker $invoker;
    
    /**
     * Method __construct
     *
     * @param Invoker $invoker invoker
     */
    public function __construct(Invoker $invoker)
    {
        $this->invoker = $invoker;
    }
    
    /**
     * Method getOutput
     *
     * @param mixed[] $routeExts routeExts
     *
     * @return string
     */
    public function getOutput(array $routeExts): string
    {
        $output = $this->invoker
            ->getInstance(Request::class)
            ->setRoutes($this->getRoutes($routeExts))
            ->setError(['class' => Error::class, 'method' => 'error'])
            ->process();
        //$this->invoker->free();
        return $output;
    }
    
    /**
     * Method getRoutes
     *
     * @param mixed[] $routeExts routeExts
     *
     * @return mixed[]
     */
    protected function getRoutes(array $routeExts): array
    {
        return $this->loadRouteCache($routeExts);
    }
    
    /**
     * Method loadRouteCache
     *
     * @param mixed[] $routeExts routeExts
     *
     * @return mixed[]
     */
    protected function loadRouteCache(array $routeExts): array
    {
        if (!file_exists($this::ROUTE_CACHE_FILE)) {
            $this->createRouteCache($routeExts);
        }
        if (!file_exists($this::ROUTE_CACHE_FILE)) {
            throw new RuntimeException(
                'Route cache error: ' . $this::ROUTE_CACHE_FILE
            );
        }
        $routes = $this->includeRoutes();
        if (!$routes) {
            $this->createRouteCache($routeExts);
            $routes = $this->includeRoutes();
        }
        return $routes;
    }
    
    /**
     * Method includeRoutes
     *
     * @return mixed[]
     */
    protected function includeRoutes(): array
    {
        $routes = [];
        include $this::ROUTE_CACHE_FILE;
        return $routes;
    }


    /**
     * Method createRouteCache
     *
     * @param mixed[] $routeExts routeExts
     *
     * @return mixed[]
     */
    protected function createRouteCache(array $routeExts): array
    {
        $merger = $this->invoker->getInstance(Merger::class);
        $routes = [];
        foreach ($routeExts as $routeExt) {
            $routes = $merger->merge($routes, $routeExt);
        }
        $this->saveRouteCache($routes);
        return $routes;
    }
    
    /**
     * Method saveRouteCache
     *
     * @param mixed[] $routes routes
     *
     * @return int
     */
    protected function saveRouteCache(array $routes): int
    {
        $export = var_export($routes, true);
        $result = file_put_contents(
            $this::ROUTE_CACHE_FILE,
            '<?php'
                . ' $routes=' . $export . ';'
        );
        if ($result === false) {
            throw new RuntimeException(
                'Unable to write: ' . $this::ROUTE_CACHE_FILE
            );
        }
        clearstatcache();
        return $result;
    }
}
