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

namespace Datamweb\CodeIgniterDEARule\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use Datamweb\CodeIgniterDEARule\Config\DEARule;

class LogsTempEmailMigration extends Migration
{
    private string $tableName;

    /**
     * @var string[]
     */
    private array $attributes;

    public function __construct(?Forge $forge = null)
    {
        /** @var DEARule $DEARuleConfig */
        $DEARuleConfig = config('DEARule');

        if ($DEARuleConfig->DBGroup !== null) {
            $this->DBGroup = $DEARuleConfig->DBGroup;
        }

        parent::__construct($forge);

        $this->tableName  = $DEARuleConfig->tableName;
        $this->attributes = ($this->db->getPlatform() === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
    }

    public function up(): void
    {
        /**
         * Temp Email Attempts Table
         * Records Temp Email attempts.
         */
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'          => ['type' => 'varchar', 'constraint' => 255],
            'try_url_string' => ['type' => 'varchar', 'constraint' => 100, 'null' => false, 'comment' => 'Path part of the current URL relative to baseURL that was attempted with the disposable email.'],
            'ip_address'     => ['type' => 'varchar', 'constraint' => 255],
            'agent_string'   => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'device'         => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'platform'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'filter_by'      => ['type' => 'varchar', 'constraint' => 20, 'comment' => 'Diagnosis by which source.'],
            'created_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($this->tableName, true, $this->attributes);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tableName, true);
    }
}
