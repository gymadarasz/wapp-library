<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Test\Account;

use Madsoft\Library\Responder\Account\AccountValidator;

/**
 * ValidatorMock
 *
 * @category  PHP
 * @package   Madsoft\Library\Test\Account
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class ValidatorMock extends AccountValidator
{
    /**
     * Method getErrors
     *
     * @param mixed[] $fields fields
     *
     * @return string[][]
     */
    public function getErrors(array $fields): array
    {
        if ($fields) {
            return [['an error']];
        }
        return [['no error']];
    }
}
