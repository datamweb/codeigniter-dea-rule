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

namespace Tests\Models;

use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Validation\Validation;
use Config\Services;
use Datamweb\CodeIgniterDEARule\Config\DEARule;
use Datamweb\CodeIgniterDEARule\Validation\DEAValidator;

/**
 * @internal
 */
final class LogsTempEmailModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    /**
     * @var bool
     */
    protected $migrate = true;

    /**
     * @var string
     */
    protected $namespace = 'Datamweb\CodeIgniterDEARule';

    private Validation $validation;
    private array $config = [
        'ruleSets' => [
            DEAValidator::class,
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->validation = $this->validation = new Validation((object) $this->config, Services::renderer());
        $this->validation->reset();
    }

    public function testSeeDBForTempEmail(): void
    {
        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertFalse($this->validation->run(['email' => 'foo@0-mail.com']));

        $this->seeInDatabase('logs_temp_email', [
            'email'     => 'foo@0-mail.com',
            'filter_by' => 'filesBlacklisted',
        ]);
    }

    public function testSeeDBForTempEmailByDomainBlacklisted(): void
    {
        /** @var DEARule $config */
        $config                    = config('DEARule');
        $config->domainBlacklisted = [
            'not-allowed-to-register.com',
        ];
        Factories::injectMock('config', 'DEARule', $config);

        $this->validation->setRules(['email' => 'is_temp_email']);
        $this->assertFalse($this->validation->run(['email' => 'foo@not-allowed-to-register.com']));

        $this->seeInDatabase('logs_temp_email', [
            'email'     => 'foo@not-allowed-to-register.com',
            'filter_by' => 'domainBlacklisted',
        ]);
    }
}
