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

use Madsoft\Library\Merger;
use Madsoft\Library\Messages;
use Madsoft\Library\Responder\TemplateResponder;
use Madsoft\Library\Template;
use Madsoft\Library\User;

/**
 * LogoutTemplateResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder\Account\Template
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class LogoutTemplateResponder extends TemplateResponder
{
    const PATH = __DIR__ . '/phtml/';
    
    protected User $user;
    
    /**
     * Method __construct
     *
     * @param Messages $messages messages
     * @param Merger   $merger   merger
     * @param Template $template template
     * @param User     $user     user
     */
    public function __construct(
        Messages $messages,
        Merger $merger,
        Template $template,
        User $user
    ) {
        parent::__construct($messages, $merger, $template);
        $this->user = $user;
    }
    
    /**
     * Method getLogoutResponse
     *
     * @return string
     *
     * @suppress PhanUnreferencedPublicMethod
     */
    public function getLogoutResponse(): string
    {
        $this->user->logout();
        
        return $this->setTplfile('login.phtml')->getSuccessResponse(
            'Logout success'
        );
    }
}
