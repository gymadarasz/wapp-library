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
 * ArrayResponder
 *
 * @category  PHP
 * @package   Madsoft\Library\Responder
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ArrayResponder extends AbstractResponder
{
    /**
     * Method getErrorResponse
     *
     * @param string     $error  error
     * @param string[][] $errors errors
     * @param mixed[]    $data   data
     *
     * @return mixed[]
     */
    public function getErrorResponse(
        string $error = AbstractResponder::LBL_ERROR,
        array $errors = [],
        array $data = []
    ): array {
        $this->messages->add('error', $error);
        return $this->getResponse($data, $errors);
    }
    
    /**
     * Method getWarningResponse
     *
     * @param string  $message message
     * @param mixed[] $data    data
     *
     * @return mixed[]
     */
    public function getWarningResponse(
        string $message = AbstractResponder::LBL_WARNING,
        array $data = []
    ): array {
        $this->messages->add('success', $message);
        return $this->getResponse($data);
    }
    
    /**
     * Method getSuccessResponse
     *
     * @param string  $message message
     * @param mixed[] $data    data
     *
     * @return mixed[]
     */
    public function getSuccessResponse(
        string $message = AbstractResponder::LBL_SUCCESS,
        array $data = []
    ): array {
        $this->messages->add('success', $message);
        return $this->getResponse($data);
    }

    /**
     * Method getResponse
     *
     * @param mixed[]    $data   data
     * @param string[][] $errors errors
     *
     * @return mixed[]
     */
    public function getResponse(array $data = [], array $errors = []): array
    {
        $messages = $this->messages->get();
        if ($messages) {
            $data['messages'] = $messages;
        }
        if ($errors) {
            $data['errors'] = $errors;
        }
        return $data;
    }

    /**
     * Method process
     *
     * @param mixed[] $data data
     *
     * @return mixed[]
     */
    protected function process(array $data): array
    {
        return $data;
    }
    
    /**
     * Method hasResponseMessageType
     *
     * @param mixed[] $response response
     * @param string  $type     type
     *
     * @return bool
     */
    public function hasResponseMessageType(array $response, string $type): bool
    {
        $this->messages->validateMessageType($type);
        return isset($response['messages']) && isset($response['messages'][$type]);
    }
    
    /**
     * Method hasResponseKey
     *
     * @param mixed[] $response response
     * @param string  $key      key
     *
     * @return bool
     */
    public function hasResponseKey(array $response, string $key): bool
    {
        return isset($response[$key]);
    }
}
