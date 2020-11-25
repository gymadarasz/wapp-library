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
 * Config
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Config extends Section
{
    const ENV = 'test';
    const PATH = __DIR__ . '/config/';
    
    protected Template $template;
    
    /**
     * Method __construct
     *
     * @param Template $template template
     * @param Merger   $merger   merger
     */
    public function __construct(
        Template $template,
        Merger $merger
    ) {
        $this->template = $template;
        
        $cfg = $this->readConfig($this::PATH . 'config.ini');
        $ext = $this->readConfig($this::PATH . 'config.' . $this::ENV . '.ini');
        $this->data = $merger->merge($cfg, $ext);
    }
    
    /**
     * Method readConfig
     *
     * @param string $filename filename
     *
     * @return mixed[]
     * @throws RuntimeException
     */
    protected function readConfig(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('Config file not found: ' . $filename);
        }
        $inistr = $this->template->process($filename, ['__DIR__' => __DIR__], '');
        $cfg = parse_ini_string($inistr, true);
        
        if (false === $cfg) {
            throw new RuntimeException('Config error: ' . $filename);
        }
        
        return $cfg;
    }
}
