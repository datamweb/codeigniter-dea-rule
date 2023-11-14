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
return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
