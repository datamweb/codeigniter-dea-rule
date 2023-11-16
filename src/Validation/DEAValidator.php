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

namespace Datamweb\CodeIgniterDEARule\Validation;

use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\UserAgent;
use Datamweb\CodeIgniterDEARule\Config\DEARule;
use Datamweb\CodeIgniterDEARule\Models\LogsTempEmailModel;

class DEAValidator
{
    protected DEARule $config;
    protected UserAgent $userAgent;
    protected Request $request;

    public function __construct()
    {
        /** @var DEARule $DEARuleConfig */
        $DEARuleConfig = config('DEARule');

        $this->config = $DEARuleConfig;

        $this->userAgent = new UserAgent();
        $this->request   = new Request();
    }

    /**
     * --------------------------------------------------------------------
     * Rule For Check Disposable Temporary E-mail
     * --------------------------------------------------------------------
     * This method was created according to the instructions for creating new validation rules in CodeIgniter4.
     *
     * @see https://codeigniter.com/user_guide/libraries/validation.html?highlight=ruls#id39
     */
    public function is_temp_email(string $value, ?string &$error = null): bool
    {
        if ($this->config->jumpFromRule === true) {
            return true;
        }

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $error = lang('DEAValidatorRule.emailInvalid');

            return false;
        }

        if (in_array($value, $this->config->emailsWhiteListed, true)) {
            return true;
        }

        $domain = trim(substr(strrchr($value, '@'), 1));

        if (in_array($domain, $this->config->domainBlacklisted, true)) {
            $this->insertToDB($value, 'domainBlacklisted');

            $error = lang('DEAValidatorRule.emailIsSuspicious');

            return false;
        }

        if ($this->checkMailIsTemporaryByFiles($domain) === true) {
            $this->insertToDB($value, 'filesBlacklisted');

            $error = lang('DEAValidatorRule.emailIsTemporary');

            return false;
        }

        return true;
    }

    /**
     * --------------------------------------------------------------------
     * Check E-mail Is Disposable Temporary By Multi DB-Files
     * --------------------------------------------------------------------
     * This function checks whether the provided email is in the Disposable/Temporary  email category or not.
     * This function will merge all the database files together and perform a final check on the total list.
     *
     * @codeCoverageIgnore
     */
    private function checkMailIsTemporaryByFiles(string $domain): bool
    {
        /** @var array<int, string> */
        $Files = [];

        foreach ($this->config->filesBlacklisted as $dbFiles) {
            $Files[] = array_map('trim', file($dbFiles));
        }

        foreach ($Files as $sub_array) {
            if (in_array($domain, $sub_array, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * --------------------------------------------------------------------
     * Insert Info For Any Try TEMP EMAIL
     * --------------------------------------------------------------------
     * This function records info(agent,ip,...) in the db for data analysis.
     *
     * @codeCoverageIgnore
     */
    private function insertToDB(string $email, string $filter_by): void
    {
        if ($this->config->recordedAttemptsIfDisposableEmails === true) {
            /** @var LogsTempEmailModel $model */
            $model = model('LogsTempEmailModel', false);

            $model->insert([
                'email'          => $email,
                'try_url_string' => uri_string(),
                'ip_address'     => $this->request->getIPAddress(),
                'agent_string'   => $this->userAgent->getAgentString(),
                'device'         => $this->getDevice(),
                'platform'       => $this->userAgent->getPlatform(),
                'filter_by'      => $filter_by,
            ]);
        }
    }

    /**
     * @see https://codeigniter.com/user_guide/libraries/user_agent.html#example
     * @codeCoverageIgnore
     */
    private function getDevice(): string
    {
        if ($this->userAgent->isBrowser()) {
            $currentAgent = $this->userAgent->getBrowser() . ' ' . $this->userAgent->getVersion();
        } elseif ($this->userAgent->isRobot()) {
            $currentAgent = $this->userAgent->getRobot();
        } elseif ($this->userAgent->isMobile()) {
            $currentAgent = $this->userAgent->getMobile();
        } else {
            $currentAgent = 'Unidentified User Agent';
        }

        return $currentAgent;
    }
}
