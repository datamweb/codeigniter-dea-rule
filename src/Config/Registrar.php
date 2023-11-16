<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter-DEA-Rule.
 *
 * (c) 2023 Datamweb <pooya_parsa_dadashi@yahoo.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Datamweb\CodeIgniterDEARule\Config;

use Datamweb\CodeIgniterDEARule\Validation\DEAValidator;

class Registrar
{
    /**
     * --------------------------------------------------------------------------
     * Registrar DEAValidator::class
     * --------------------------------------------------------------------------
     * Register the `is-temp-email` rule for used like CI4 rules.
     *
     * @return array<string, array<class-string<DEAValidator>>>
     */
    public static function Validation(): array
    {
        return [
            'ruleSets' => [
                DEAValidator::class,
            ],
        ];
    }
}
