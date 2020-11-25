<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Responder;

use Madsoft\Library\Json;
use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\StringResponder;

/**
 * JsonResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class JsonResponder extends StringResponder
{
    protected Json $json;

    /**
     * Method __construct
     *
     * @param Messages $messages messages
     * @param Merger   $merger   merger
     * @param Json     $json     json
     */
    public function __construct(Messages $messages, Merger $merger, Json $json)
    {
        parent::__construct($messages, $merger);
        $this->json = $json;
    }

    /**
     * Method process
     *
     * @param mixed[] $data data
     *
     * @return string
     */
    protected function process(array $data): string
    {
        return $this->json->encode($data);
    }
}
