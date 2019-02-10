<?php
$dbName = 'koop';
if (true === defined('IS_IN_TEST_ENV') && true === IS_IN_TEST_ENV) {
    $dbName = 'koop_test';
}

return [
    'table_prefix'  => 'koop_',
    'driver'        => 'pdo_mysql',
    'user'          => 'root',
    'host'          => '127.0.0.1',
    'password'      => 'root',
    'port'          => 3306,
    'dbname'        => $dbName,
    'charset'       => 'utf8',
    'driverOptions' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ],
];