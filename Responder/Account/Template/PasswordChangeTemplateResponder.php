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
use Madsoft\Library\Responder\Account\PasswordChangeArrayResponder;
use Madsoft\Library\Responder\TemplateResponder;

/**
 * PasswordChangeTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class PasswordChangeTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    /**
     * Method getPasswordChangeResponse
     *
     * @param PasswordChangeArrayResponder $arrayResponder passwordChanger
     * @param Params                       $params         params
     *
     * @return string
     */
    public function getPasswordChangeResponse(
        PasswordChangeArrayResponder $arrayResponder,
        Params $params
    ): string {
        $responseArray = $arrayResponder->getPasswordChangeResponse($params);
        if ($arrayResponder->hasResponseMessageType($responseArray, 'error')) {
            return $this->setTplfile('change.phtml')->getResponse($responseArray);
        }
        return $this->setTplfile('login.phtml')->getResponse($responseArray);
    }
}
