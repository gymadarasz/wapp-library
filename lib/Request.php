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

use Exception;
use RuntimeException;

/**
 * Request
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Request
{
    const QUERY_KEY = 'q';
    
    /**
     * Variable $routes
     *
     * @var string[][][][]
     */
    protected array $routes;
    
    /**
     * Variable $notFound
     *
     * @var string[]
     */
    protected array $error;
    
    protected Invoker $invoker;
    protected Logger $logger;
    protected User $user;
    protected Server $server;
    protected Params $params;
    //    protected Json $json;
    protected Csrf $csrf;

    /**
     * Method __construct
     *
     * @param Invoker $invoker invoker
     * @param Logger  $logger  logger
     * @param User    $user    user
     * @param Server  $server  server
     * @param Params  $params  params
     *                         @ param Json    $json    json
     * @param Csrf    $csrf    csrf
     */
    public function __construct(
        Invoker $invoker,
        Logger $logger,
        User $user,
        Server $server,
        Params $params,
        //        Json $json,
        Csrf $csrf
    ) {
        $this->invoker = $invoker;
        $this->logger = $logger;
        $this->user = $user;
        $this->server = $server;
        $this->params = $params;
        //        $this->json = $json;
        $this->csrf = $csrf;
    }
    
    /**
     * Method setRoutes
     *
     * @param string[][][][] $routes routes
     *
     * @return self
     */
    public function setRoutes(array $routes): self
    {
        $this->routes = $routes;
        return $this;
    }
    
    /**
     * Method setError
     *
     * @param string[] $error error
     *
     * @return self
     */
    public function setError(array $error): self
    {
        $this->error = $error;
        return $this;
    }
      
    /**
     * Method process
     *
     * @return string
     */
    public function process(): string
    {
        try {
            $area = $this->user->isVisitor() ? 'public' : 'protected';
            $method = $this->server->get('REQUEST_METHOD');
            if ($method === 'POST' && !$this->csrf->check()) {
                throw new RuntimeException('CSRF token mismatch.');
            }
            $query = $this->params->get(self::QUERY_KEY, '');
            $response = $this->call($area, $method, $query);
        } catch (Exception $exception) {
            $this->logger->exception($exception);
            $response = $this->invoker->invoke($this->error);
        }
        //        if (is_array($response)) {
        //            return $this->json->encode($response);
        //        }
        return $response;
    }

    /**
     * Method call
     *
     * @param string $area   area
     * @param string $method method
     * @param string $query  query
     *
     * @return mixed
     */
    protected function call(string $area, string $method, string $query)
    {
        if (!isset($this->routes[$area])
            || !isset($this->routes[$area][$method])
            || !isset($this->routes[$area][$method][$query])
        ) {
            throw new RuntimeException(
                "Route not found for requested area: '$area', "
                    . "method: '$method', "
                    . "query: '" . self::QUERY_KEY . "=$query'"
            );
        }
        return $this->invoker->invoke($this->routes[$area][$method][$query]);
    }
}
