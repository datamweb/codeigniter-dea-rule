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

class DEARule
{
    /**
     * --------------------------------------------------------------------
     * Domain Database Files Used Blacklisted
     * --------------------------------------------------------------------
     * We use a large database to check temporary emails. However,
     * If you want to specifically identify the domain as Temporary Email, add it there.
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public array $filesBlacklisted = [
        'https://raw.githubusercontent.com/wesbos/burner-email-providers/master/emails.txt',
        // 'https://raw.githubusercontent.com/disposable-email-domains/disposable-email-domains/master/disposable_email_blocklist.conf',
        // 'https://gist.githubusercontent.com/saaiful/dd2b4b34a02171d7f9f0b979afe48f65/raw/2ad5590be72b69a51326b3e9d229f615e866f2e5/blocklist.txt',

        // APPPATH . 'myCustomDomainBlacklisted.txt',
    ];

    /**
     * --------------------------------------------------------------------
     * Add New Domain To Domains Blacklisted
     * --------------------------------------------------------------------
     * We use a large database to check temporary emails. However,
     * If you want to specifically identify the domain as Temporary Email, add it there.
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public array $domainBlacklisted = [
        // 'not-allowed-to-register.com',
    ];

    /**
     * --------------------------------------------------------------------
     * Add Emails To White Listed
     * --------------------------------------------------------------------
     * The email added here will be considered valid anyway.
     *
     * @var string[]
     * @phpstan-var list<string>
     */
    public array $emailsWhiteListed = [
        // 'foo@allowed-to-register.com',
    ];

    /**
     * --------------------------------------------------------------------
     * Skipping the rule `is_temp_email`
     * --------------------------------------------------------------------
     * If for any reason you want rule `is_temp_email` checking to be disabled, set it to true.
     */
    public bool $jumpFromRule = false;

    /**
     * --------------------------------------------------------------------
     * Customize the DB group used for Model/Migration
     * --------------------------------------------------------------------
     */
    public ?string $DBGroup = null;

    /**
     * --------------------------------------------------------------------
     * Customize The Temp Email Attempts Table name
     * --------------------------------------------------------------------
     * All attempts made with disposable emails are recorded in this table.
     * The information in this table will help you to have accurate statistics of attempts to use disposable emails.
     * You can block users with a specific IP by checking IPs if needed.
     */
    public string $tableName = 'logs_temp_email';

    /**
     * --------------------------------------------------------------------
     * Set ON/OFF Attempts If Disposable Email Detection
     * --------------------------------------------------------------------
     * By default, if a Disposable Email is detected, the user's data is stored in the DB.
     * If you can't save data, set `false`.
     */
    public bool $recordedAttemptsIfDisposableEmails = true;
}
