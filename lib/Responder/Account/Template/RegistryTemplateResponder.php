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
use Madsoft\Library\Responder\Account\RegistryArrayResponder;
use Madsoft\Library\Responder\TemplateResponder;
use Madsoft\Library\Session;

/**
 * RegistryTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class RegistryTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    /**
     * Method getRegistryFormResponse
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getRegistryFormResponse(): string
    {
        return $this->setTplfile('registry.phtml')->getResponse();
    }
    
    
    /**
     * Method getRegistryResponse
     *
     * @param RegistryArrayResponder $arrayResponder arrayResponder
     * @param Params                 $params         params
     * @param Session                $session        session
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getRegistryResponse(
        RegistryArrayResponder $arrayResponder,
        Params $params,
        Session $session
    ): string {
        $arrayResponse = $arrayResponder->getRegistryResponse($params, $session);
        if ($arrayResponder->hasResponseMessageType($arrayResponse, 'error')) {
            return $this->setTplfile('registry.phtml')->getResponse($arrayResponse);
        }
        return $this->setTplfile('activate.phtml')->getResponse($arrayResponse);
    }
    
    /**
     * Method getResendResponse
     *
     * @param RegistryArrayResponder $arrayResponder arrayResponder
     * @param Session                $session        session
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getResendResponse(
        RegistryArrayResponder $arrayResponder,
        Session $session
    ): string {
        return $this->setTplfile('activate.phtml')->getResponse(
            $arrayResponder->getResendResponse($session)
        );
    }
}
