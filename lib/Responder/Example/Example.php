<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Example
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Responder\Example;

/**
 * Example
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Example
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 *
 * @suppress PhanUnreferencedPublicClassConstant
 */
class Example
{
    const ROUTES = [
        'public' => [
            'GET' => [
                'example' => [Example::class, 'getPublic'],
            ],
        ],
        'protected' => [
            'GET' => [
                '' => [Example::class, 'getProtected'],
                'example' => [Example::class, 'getProtected'],
            ],
        ],
    ];
    /**
     * Method getPublic
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getPublic(): string
    {
        return 'Public Index Page';
    }
    
    /**
     * Method getProtected
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getProtected(): string
    {
        return 'Restricted Index Page';
    }
}
