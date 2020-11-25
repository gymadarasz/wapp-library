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
use Madsoft\Library\Responder\StringResponder;
use Madsoft\Library\Template;
use RuntimeException;

/**
 * TemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class TemplateResponder extends StringResponder
{
    const PATH = null;
    
    protected string $tplfile = '';
    
    protected Template $template;

    /**
     * Method __construct
     *
     * @param Messages $messages messages
     * @param Merger   $merger   merger
     * @param Template $template template
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Template $template
    ) {
        parent::__construct($messages, $merger);
        $this->template = $template;
    }

    /**
     * Method setTplfile
     *
     * @param string $tplfile tplfile
     *
     * @return self
     */
    public function setTplfile(string $tplfile): self
    {
        $this->tplfile = $tplfile;
        return $this;
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
        if (!$this->tplfile) {
            throw new RuntimeException('Template file is not set.');
        }
        return $this->template->process($this->tplfile, $data, $this::PATH);
    }
}
