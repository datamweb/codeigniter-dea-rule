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

namespace Datamweb\CodeIgniterDEARule\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;
use Throwable;

class DEARulePublish extends BaseCommand
{
    protected $group       = 'CodeIgniter DEA Rule';
    protected $name        = 'dea-rule:publish';
    protected $description = 'Publish DEARule config file into the current application.';

    public function run(array $params): void
    {
        // Use the Autoloader to figure out the module path
        $source = service('autoloader')->getNamespace('Datamweb\\CodeIgniterDEARule')[0];

        $publisher = new Publisher($source, APPPATH);

        try {
            // Add only the desired components
            $publisher->addPaths([
                'Config\DEARule.php',
            ])->merge(false); // Be careful not to overwrite anything
        } catch (Throwable $e) {
            $this->showError($e);

            return;
        }

        // If publication succeeded then update namespaces
        foreach ($publisher->getPublished() as $file) {
            // Replace the namespace
            $contents = file_get_contents($file);

            $contents = str_replace('namespace Datamweb\\CodeIgniterDEARule\\Config', 'namespace Config', $contents);
            $contents = str_replace('class DEARule', 'class DEARule extends \\Datamweb\\CodeIgniterDEARule\\Config\\DEARule', $contents);

            file_put_contents($file, $contents);
        }
        CLI::write(CLI::color('  Published! ', 'green') . 'You can customize the configuration by editing the "app/Config/DEARule.php" file.');
    }
}
