<?php
$defaultConfig = [
    'DB_DSN' => 'mysql:host=localhost;dbname=paranoid;charset=utf8',
    'DB_USER' => 'paranoid',
    'DB_PASSWORD' => 'paranoid',
];

function config_get(string $name) {
    global $defaultConfig;
    if (isset($_ENV[$name])) {
        return $_ENV[$name];
    }
    return $defaultConfig[$name];
}
