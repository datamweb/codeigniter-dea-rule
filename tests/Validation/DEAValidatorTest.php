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

namespace Tests\Validation;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Validation\ValidationInterface;
use Config\Services;
use Datamweb\CodeIgniterDEARule\Config\DEARule;

/**
 * @internal
 */
final class DEAValidatorTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    private ValidationInterface $validation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = Services::validation();
        $this->validation->reset();

        /** @var DEARule $config */
        $config                                     = config('DEARule');
        $config->recordedAttemptsIfDisposableEmails = false;

        Factories::injectMock('config', 'DEARule', $config);
    }

    /**
     * @dataProvider provideIsTempEmail
     *
     * @param array<string, string> $data
     */
    public function testIsTempEmail(array $data, bool $expected): void
    {
        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertSame($expected, $this->validation->run($data));
        // $this->validation->run($data);
        // $this->assertSame(
        //     ['email' => lang('DEAValidatorRule.emailInvalid')],
        //     $this->validation->getErrors('email')
        // );
    }

    /**
     * @return array<array<array<string, string>|bool>> $data
     */
    public static function provideIsTempEmail(): iterable
    {
        yield from [
            // temp email from https://raw.githubusercontent.com/wesbos/burner-email-providers/master/emails.txt
            [['email' => 'foo@0-mail.com'], false],
            [['email' => 'biz@0-mail.com'], false],
            [['email' => 'any@0-mail.com'], false],
            [['email' => 'foo@kino24.ru'], false],
            [['email' => 'biz@kino24.ru'], false],
            [['email' => 'any@kino24.ru'], false],

            // no temp email
            [['email' => 'email@no-temp-email.com'], true],
            [['email' => 'email@no-temp-email.com'], true],

            // invalid email format
            [['email' => 'email-invalid.com'], false],
            [['email' => 'emai#email.com'], false],
        ];
    }

    /**
     * @dataProvider provideIsTempEmailByMutiDBFiles
     *
     * @param array<string, string> $data
     */
    public function testIsTempEmailByMutiDBFiles(array $data, bool $expected): void
    {
        // Off data storage in the DB due to not running the migration
        /** @var DEARule $config */
        $config                   = config('DEARule');
        $config->filesBlacklisted = [
            'https://raw.githubusercontent.com/wesbos/burner-email-providers/master/emails.txt',
            'https://gist.githubusercontent.com/saaiful/dd2b4b34a02171d7f9f0b979afe48f65/raw/2ad5590be72b69a51326b3e9d229f615e866f2e5/blocklist.txt',
        ];

        Factories::injectMock('config', 'DEARule', $config);

        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertSame($expected, $this->validation->run($data));
    }

    /**
     * @return array<array<array<string, string>|bool>> $data
     */
    public static function provideIsTempEmailByMutiDBFiles(): iterable
    {
        yield from [
            // temp email from https://raw.githubusercontent.com/wesbos/burner-email-providers/master/emails.txt
            [['email' => 'foo@0-mail.com'], false],
            [['email' => 'biz@0-mail.com'], false],
            [['email' => 'any@0-mail.com'], false],
            [['email' => 'foo@kino24.ru'], false],
            [['email' => 'biz@kino24.ru'], false],
            [['email' => 'any@kino24.ru'], false],
            // temp email from 'https://gist.githubusercontent.com/saaiful/dd2b4b34a02171d7f9f0b979afe48f65/raw/2ad5590be72b69a51326b3e9d229f615e866f2e5/blocklist.txt'
            [['email' => 'foo@a45.in'], false],
            [['email' => 'biz@a45.in'], false],
            [['email' => 'any@a45.in'], false],
            [['email' => 'foo@cachedot.net'], false],
            [['email' => 'biz@cachedot.net'], false],
            [['email' => 'any@cachedot.net'], false],
        ];
    }

    /**
     * @dataProvider provideIsTempEmailByDomainBlacklisted
     *
     * @param array<string, string> $data
     */
    public function testIsTempEmailByDomainBlacklisted(array $data, bool $expected): void
    {
        /** @var DEARule $config */
        $config                    = config('DEARule');
        $config->domainBlacklisted = [
            'not-allowed-to-register.com',
            'not-allowed-to-login.com',
        ];

        Factories::injectMock('config', 'DEARule', $config);

        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertSame($expected, $this->validation->run($data));
    }

    /**
     * @return array<array<array<string, string>|bool>> $data
     */
    public static function provideIsTempEmailByDomainBlacklisted(): iterable
    {
        yield from [
            [['email' => 'foo@not-allowed-to-register.com'], false],
            [['email' => 'biz@not-allowed-to-register.com'], false],
            [['email' => 'any@not-allowed-to-register.com'], false],
            [['email' => 'foo@not-allowed-to-login.com'], false],
            [['email' => 'biz@not-allowed-to-login.com'], false],
            [['email' => 'any@not-allowed-to-login.com'], false],
        ];
    }

    /**
     * @dataProvider provideEmailIsDomainBlacklistedButEmailsWhiteListed
     *
     * @param array<string, string> $data
     */
    public function testEmailIsDomainBlacklistedButEmailsWhiteListed(array $data, bool $expected): void
    {
        /** @var DEARule $config */
        $config                    = config('DEARule');
        $config->domainBlacklisted = [
            'not-allowed-to-register.com',
            'not-allowed-to-login.com',
        ];
        $config->emailsWhiteListed = [
            'foo@not-allowed-to-register.com',
            'foo@not-allowed-to-login.com',
        ];

        Factories::injectMock('config', 'DEARule', $config);

        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertSame($expected, $this->validation->run($data));
    }

    /**
     * @return array<array<array<string, string>|bool>> $data
     */
    public static function provideEmailIsDomainBlacklistedButEmailsWhiteListed(): iterable
    {
        yield from [
            [['email' => 'foo@not-allowed-to-register.com'], true],
            [['email' => 'foo@not-allowed-to-login.com'], true],
        ];
    }
}
