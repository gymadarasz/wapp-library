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

/**
 * User
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class User
{
    protected Session $session;
    
    /**
     * Method __construct
     *
     * @param Session $session session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        //        $this->session->destroy();
    }
    
    /**
     * Method isVisitor
     *
     * @return bool
     */
    public function isVisitor(): bool
    {
        return !$this->session->get('uid', 0);
    }
    
    /**
     * Method login
     *
     * @param int $uid uid
     *
     * @return void
     */
    public function login(int $uid): void
    {
        $this->session->set('uid', $uid);
    }
    
    /**
     * Method logout
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->set('uid', 0);
    }
}
