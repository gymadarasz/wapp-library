<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library\Validator\Rule;

/**
 * HasLower
 *
 * @category  PHP
 * @package   Madsoft\Library\Validator\Rule
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class HasLower extends RegexMatch
{
    const MESSAGE = "Doesn't contains any lowercase letter";
    const PATTERN = '/[a-z]/';
}
