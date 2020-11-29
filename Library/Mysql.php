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

use mysqli;
use mysqli_result;
use RuntimeException;

/**
 * Mysql
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Mysql
{
    protected mysqli $mysqli;
    protected bool $connected = false;
    
    protected Config $config;
    
    /**
     * Method __construct
     *
     * @param Config $config config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Method transStart
     *
     * @return void
     * @throws RuntimeException
     */
    public function transStart(): void
    {
        $this->connect();
        if (!$this->mysqli->autocommit(false)) {
            throw new RuntimeException('Mysql transaction start failed.');
        }
    }
    
    /**
     * Method transCommit
     *
     * @return void
     * @throws RuntimeException
     */
    public function transCommit(): void
    {
        if (!$this->mysqli->commit()) {
            throw new RuntimeException('Mysql transaction commit failed.');
        }
        $this->transStop();
    }
    
    /**
     * Method transRollback
     *
     * @return void
     * @throws RuntimeException
     */
    public function transRollback(): void
    {
        if (!$this->mysqli->rollback()) {
            throw new RuntimeException('Mysql transaction rollback failed.');
        }
        $this->transStop();
    }
    
    /**
     * Method transStop
     *
     * @return void
     * @throws RuntimeException
     */
    protected function transStop(): void
    {
        if (!$this->mysqli->autocommit(true)) {
            throw new RuntimeException('Mysql transaction stop failed.');
        }
    }
    
    /**
     * Method connect
     *
     * @return void
     * @throws RuntimeException
     */
    protected function connect(): void
    {
        if ($this->connected) {
            return;
        }
        $dbcfg = $this->config->get('database');
        $this->mysqli = new mysqli(
            $dbcfg->get('host'),
            $dbcfg->get('user'),
            $dbcfg->get('password'),
            $dbcfg->get('database')
        );
        if ($this->mysqli->connect_error) {
            throw new RuntimeException(
                'MySQL connection error: (' . $this->mysqli->connect_errno . ')' .
                    $this->mysqli->connect_error
            );
        }
        $this->connected = true;
    }

    /**
     * Method escape
     *
     * @param string $value value
     *
     * @return string
     */
    public function escape(string $value): string
    {
        $this->connect();
        return $this->mysqli->escape_string($value);
    }

    /**
     * Method selectOne
     *
     * @param string $query query
     *
     * @return string[]
     * @throws RuntimeException
     */
    public function selectOne(string $query): array
    {
        $this->connect();
        $result = $this->mysqli->query($query);
        if ($result instanceof mysqli_result) {
            // return (new Row())->setFields($result->fetch_assoc() ?: []);
            return $result->fetch_assoc() ?: [];
        }
        throw new RuntimeException(
            "MySQL query error:\n$query\nMessage: {$this->mysqli->error}"
        );
    }

    /**
     * Method select
     *
     * @param string $query query
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    public function select(string $query): array
    {
        $this->connect();
        $result = $this->mysqli->query($query);
        if ($result instanceof mysqli_result) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                // $rows[] = (new Row())->setFields($row);
                $rows[] = $row;
            }
            return $rows;
        }
        throw new RuntimeException(
            "MySQL query error:\n$query\nMessage: {$this->mysqli->error}"
        );
    }

    /**
     * Method query
     *
     * @param string $query query
     *
     * @return bool
     * @throws RuntimeException
     */
    protected function query(string $query): bool
    {
        $this->connect();
        $ret = (bool)$this->mysqli->query($query);
        if ($ret) {
            return $ret;
        }
        throw new RuntimeException(
            "MySQL query error:\n$query\nMessage: {$this->mysqli->error}"
        );
    }

    /**
     * Method update
     *
     * @param string $query query
     *
     * @return int
     */
    public function update(string $query): int
    {
        if (!$this->query($query)) {
            return 0;
        }
        return $this->mysqli->affected_rows;
    }
    
    /**
     * Method delete
     *
     * @param string $query query
     *
     * @return int
     */
    public function delete(string $query): int
    {
        return $this->update($query);
    }

    /**
     * Method insert
     *
     * @param string $query query
     *
     * @return int
     */
    public function insert(string $query): int
    {
        if (!$this->query($query)) {
            return 0;
        }
        return (int)$this->mysqli->insert_id;
    }
}
