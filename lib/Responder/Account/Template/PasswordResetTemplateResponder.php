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
use Madsoft\Library\Responder\Account\PasswordResetArrayResponder;
use Madsoft\Library\Responder\TemplateResponder;

/**
 * PasswordResetTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class PasswordResetTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    /**
     * Method getPasswordResetFormResponse
     *
     * @param PasswordResetArrayResponder $arrayResponder arrayResponder
     * @param Params                      $params         params
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getPasswordResetFormResponse(
        PasswordResetArrayResponder $arrayResponder,
        Params $params
    ): string {
        $arrayResponse = $arrayResponder->getPasswordResetFormResponse($params);
        if ($arrayResponder->hasResponseKey($arrayResponse, 'token')) {
            return $this->setTplfile('change.phtml')->getResponse($arrayResponse);
        }
        return $this->setTplfile('reset.phtml')->getResponse($arrayResponse);
    }
    
    /**
     * Method getPasswordResetResponse
     *
     * @param PasswordResetArrayResponder $arrayResponder arrayResponder
     * @param Params                      $params         params
     *
     * @return string
     */
    public function getPasswordResetRequestResponse(
        PasswordResetArrayResponder $arrayResponder,
        Params $params
    ): string {
        return $this->setTplfile('reset.phtml')->getResponse(
            $arrayResponder->getPasswordResetRequestResponse($params)
        );
    }
}
