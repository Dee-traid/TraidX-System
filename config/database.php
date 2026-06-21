<?php

require_once __DIR__ . '/../vendor/autoload.php';

if(file_exists(__DIR__ . '/../.env')){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

return[
    'DRIVER' => getenv('DB_DRIVER'),
    'HOST' => getenv('DB_HOST'),
    'PORT' => getenv('DB_PORT'),
    'DATABASE' => getenv('DB_NAME'),
    'USERNAME' => getenv('DB_USER'),
    'PASSWORD' => getenv('DB_PASS'),

];