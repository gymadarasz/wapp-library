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
 * Row
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Row implements Assoc
{
    
    /**
     * Variable $fields
     *
     * @var string[]
     */
    protected array $fields;
    
    /**
     * Method setFields
     *
     * @param string[] $fields fields
     *
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }
    
    /**
     * Method get
     *
     * @param string $key     key
     * @param string $default default
     *
     * @return ?string
     */
    public function get(string $key, $default = null)
    {
        return $this->fields[$key] ?? $default;
    }

    /**
     * Method has
     *
     * @param string $key key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->fields[$key]);
    }

    /**
     * Method set
     *
     * @param string $key   key
     * @param string $value value
     *
     * @return Assoc
     */
    public function set(string $key, $value): Assoc
    {
        $this->fields[$key] = $value;
        return $this;
    }
    
    /**
     * Method getFields
     *
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}
