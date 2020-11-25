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
use Madsoft\Library\Responder\Account\LoginArrayResponder;
use Madsoft\Library\Responder\TemplateResponder;

/**
 * LoginTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class LoginTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    /**
     * Method getLoginFormResponse
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLoginFormResponse(): string
    {
        return $this->setTplfile('login.phtml')->getResponse();
    }
    
    /**
     * Method getLoginResponse
     *
     * @param LoginArrayResponder $arrayResponder arrayResponder
     * @param Params              $params         params
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLoginResponse(
        LoginArrayResponder $arrayResponder,
        Params $params
    ): string {
        $arrayResponse = $arrayResponder->getLoginResponse($params);
        if ($arrayResponder->hasResponseMessageType($arrayResponse, 'error')) {
            return $this->setTplfile('login.phtml')->getResponse($arrayResponse);
        }
        return $this
            ->setTplfile('../../../Example/phtml/example.phtml')
            ->getResponse($arrayResponse);
    }
}
