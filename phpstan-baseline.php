<?php declare(strict_types = 1);

$ignoreErrors = [];

$ignoreErrors[] = [
    'message' => '#^Property Tests\\\\Support\\\\Config\\\\Registrar\\:\\:\\$dbConfig type has no value type specified in iterable type array\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/_support/Config/Registrar.php',
];
$ignoreErrors[] = [
    'message' => '#^Method Tests\\\\Support\\\\Config\\\\Registrar\\:\\:Database\\(\\) return type has no value type specified in iterable type array\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/_support/Config/Registrar.php',
];
$ignoreErrors[] = [
    'message' => '#^Property Tests\\\\Validation\\\\DEAValidatorTest\\:\\:\\$config type has no value type specified in iterable type array\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/Validation/DEAValidatorTest.php',
];
$ignoreErrors[] = [
    'message' => '#^Parameter \\#1 \\$config of class CodeIgniter\\\\Validation\\\\Validation constructor expects Config\\\\Validation, stdClass given\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/Validation/DEAValidatorTest.php',
];
$ignoreErrors[] = [
    'message' => '#^Property Tests\\\\Models\\\\LogsTempEmailModelTest\\:\\:\\$config type has no value type specified in iterable type array\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/Models/LogsTempEmailModelTest.php',
];
$ignoreErrors[] = [
    'message' => '#^Parameter \\#1 \\$config of class CodeIgniter\\\\Validation\\\\Validation constructor expects Config\\\\Validation, stdClass given\\.$#',
    'count' => 1,
    'path' => __DIR__ . '/tests/Models/LogsTempEmailModelTest.php',
];
return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
