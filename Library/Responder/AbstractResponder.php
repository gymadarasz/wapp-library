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

use Madsoft\Library\Merger;
use Madsoft\Library\Messages;

/**
 * AbstractResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class AbstractResponder
{
    const LBL_SUCCESS = 'Operation success';
    const LBL_ERROR = 'Operation failed';
    const LBL_WARNING = 'Operation success but some errors occurred';
    
    protected Messages $messages;
    protected Merger $merger;

    /**
     * Method __construct
     *
     * @param Messages $messages messages
     * @param Merger   $merger   merger
     */
    public function __construct(Messages $messages, Merger $merger)
    {
        $this->messages = $messages;
        $this->merger = $merger;
    }
}
