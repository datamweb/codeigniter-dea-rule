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

namespace Datamweb\CodeIgniterDEARule\Models;

use CodeIgniter\Model;
use Datamweb\CodeIgniterDEARule\Config\DEARule;

class LogsTempEmailModel extends Model
{
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    /**
     * @var string[]
     */
    protected $allowedFields = [
        'email', 'try_url_string', 'ip_address', 'agent_string', 'device', 'platform', 'filter_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected function initialize(): void
    {
        /** @var DEARule $config */
        $config = config('DEARule');

        if ($config->DBGroup !== null) {
            $this->DBGroup = $config->DBGroup;
        }

        $this->table = $config->tableName;
    }
}
