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

namespace Tests\Database\Migrations;

use CodeIgniter\CLI\CLI;
use CodeIgniter\Config\Factories;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\StreamFilterTrait;

/**
 * @internal
 */
final class LogsTempEmailMigrationTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use StreamFilterTrait;

    protected function setUp(): void
    {
        parent::setUp();

        putenv('NO_COLOR=1');
        CLI::init();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        putenv('NO_COLOR');
        CLI::init();
        $this->resetStreamFilterBuffer();
    }

    public function testMigration(): void
    {
        command('migrate -n Datamweb\\\\CodeIgniterDEARule');

        $result   = $this->getNormalizedResult();
        $expected = 'Running: (Datamweb\CodeIgniterDEARule) 2023-11-11-105553_Datamweb\CodeIgniterDEARule\Database\Migrations\LogsTempEmailMigration';
        $this->assertStringContainsString($expected, $result);

        // $this->resetStreamFilterBuffer();

        // command('db:table logs_temp_email');
        // $result = $this->getNormalizedResult();

        // $expected = 'Data of Table "logs_temp_email":';
        // $this->assertStringContainsString($expected, $result);

        // $expected = <<<'EOL'
        //     +----+-------+----------------+------------+--------------+--------+----------+-----------+------------+
        //     | id | email | try_url_string | ip_address | agent_string | device | platform | filter_by | created_at |
        //     +----+-------+----------------+------------+--------------+--------+----------+-----------+------------+
        //     EOL;
        // $this->assertStringContainsString($expected, $result);
    }

    // public function testRunMigrationByNewTableName(): void
    // {
    //     // !! Need Refactor If you Can Send PR
    //     // $this->markTestIncomplete('This test has not been implemented yet.');

    //     $config            = config('DEARule');
    //     $config->tableName = 'my_logs_temp_email';

    //     Factories::injectMock('config', 'DEARule', $config);

    //     command('migrate -n Datamweb\\\\CodeIgniterDEARule');

    //     $this->resetStreamFilterBuffer();

    //     command('db:table my_logs_temp_email');
    //     $result = $this->getNormalizedResult();

    //     $expected = 'Data of Table "my_logs_temp_email":';
    //     $this->assertStringContainsString($expected, $result);
    // }

    private function getNormalizedResult(): string
    {
        return str_replace(PHP_EOL, "\n", $this->getStreamFilterBuffer());
    }
}
