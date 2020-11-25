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

use Madsoft\Library\Responder\AbstractResponder;

/**
 * StringResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
abstract class StringResponder extends AbstractResponder
{
    /**
     * Method getErrorResponse
     *
     * @param string     $error  error
     * @param string[][] $errors errors
     * @param mixed[]    $data   data
     *
     * @return string
     */
    public function getErrorResponse(
        string $error = AbstractResponder::LBL_ERROR,
        array $errors = [],
        array $data = []
    ): string {
        $this->messages->add('error', $error);
        return $this->getResponse($data, $errors);
    }
    
    //    /**
    //     * Method getWarningResponse
    //     *
    //     * @param string  $message message
    //     * @param mixed[] $data    data
    //     *
    //     * @return string
    //     */
    //    public function getWarningResponse(
    //        string $message = AbstractResponder::LBL_WARNING,
    //        array $data = []
    //    ): string {
    //        $this->messages->add('warning', $message);
    //        return $this->getResponse($data);
    //    }
    
    /**
     * Method getSuccessResponse
     *
     * @param string  $message message
     * @param mixed[] $data    data
     *
     * @return string
     */
    public function getSuccessResponse(
        string $message = AbstractResponder::LBL_SUCCESS,
        array $data = []
    ): string {
        $this->messages->add('success', $message);
        return $this->getResponse($data);
    }

    /**
     * Method getResponse
     *
     * @param mixed[]    $data   data
     * @param string[][] $errors errors
     *
     * @return string
     */
    public function getResponse(array $data = [], array $errors = []): string
    {
        return $this->process(
            $this->merger->merge(
                [
                    'messages' => $this->messages->get(),
                    'errors' => $errors,
                ],
                $data
            )
        );
    }

    /**
     * Method process
     *
     * @param mixed[] $data data
     *
     * @return string
     */
    abstract protected function process(array $data): string;
}
