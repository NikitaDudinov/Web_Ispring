<?php
declare(strict_types=1);
namespace App\Infrastructure;
class ConnectionProvider
{
    public static function getConnection(): \PDO
    {
    $ini_array = parse_ini_file("php.ini");
    return new \PDO("mysql:host={$ini_array['host']};dbname={$ini_array['dbname']}", "{$ini_array['user']}", "{$ini_array['password']}");
    }
}