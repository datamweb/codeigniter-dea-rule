# Installation

These instructions assume that you have already installed the [**CodeIgniter 4 app starter**](https://codeigniter.com/user_guide/installation/installing_composer.html#installation) as the basis for your new project, set up your `.env` file, and created a database that you can access via the Spark CLI script.

## Requirements

- [Composer](https://getcomposer.org) _If you install through Composer_.
- [Codeigniter](https://codeigniter4.github.io/CodeIgniter4/installation/installing_composer.html#installation) **v4.4.3** or later
- PHP 7.4.3+

## Composer Installation

Installation is done through [Composer](https://getcomposer.org). The example assumes you have it installed globally.
If you have it installed as a phar, or otherwise you will need to adjust the way you call composer itself.

```console
composer require datamweb/codeigniter-dea-rule
```

## Manually Installation

You can manually install **`codeigniter-dea-rule`** by extracting the project file to path `app\ThirdParty\codeigniter-dea-rule` and then adding:

```php
public $psr4 = [
  // add this line
  'Datamweb\\CodeIgniterDEARule' => APPPATH . 'ThirdParty/codeigniter-dea-rule/src',
];
```
to the app\Config\Autoload.php file, however we do **not recommend** this. Please use the Composer.

## Add Required Table
**`codeigniter-dea-rule`** After the efforts of users, it collects information with DEA emails, so that the administrator can have accurate statistical information.
These attempts are stored in the `logs_temp_email` table. Therefore, there is a need to create a new table to store this data.
Run the following command to create the desired table.

```php
php spark migrate -n Datamweb\CodeIgniterDEARule
```

## Publish DEARule Config in app

**`codeigniter-dea-rule`** uses a configuration file named **DEARule.php**. Therefore, by setting it, if you update for a new version, file **DEARule.php** may be overwritten, which causes the settings you have already made to be lost.

To solve this problem, run the following command to create a new configuration file in path **app/Config/DEARule.php**. From now on, you should make all the settings in file **app/Config/DEARule.php** Not **vendor\datamweb\codeigniter-dea-rule\src\Config\DEARule.php**.

```console
php spark dea-rule:publish
```
