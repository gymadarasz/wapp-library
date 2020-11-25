<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Responder\Account\Template;

use Madsoft\Library\Params;
use Madsoft\Library\Responder\Account\ActivateArrayResponder;
use Madsoft\Library\Responder\TemplateResponder;
use Madsoft\Library\Session;

/**
 * ActivateTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ActivateTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    /**
     * Method getActivateResponse
     *
     * @param ActivateArrayResponder $arrayResponder activator
     * @param Params                 $params         params
     * @param Session                $session        session
     *
     * @return string
     */
    public function getActivateResponse(
        ActivateArrayResponder $arrayResponder,
        Params $params,
        Session $session
    ): string {
        return $this->setTplfile('activated.phtml')->getResponse(
            $arrayResponder->getActivateResponse($params, $session)
        );
    }
}
