<?php
declare(strict_types=1);

namespace App\Database;

class ConnectionProvider
{
    // Создаёт объект PDO, представляющий подключение к MySQL.
    public static function connectDatabase(): \PDO
    {
        $dsn = self::getEnvParameter('APP_DATABASE_DSN', 'mysql:host=127.0.0.1;dbname=php_course');
        $user = self::getEnvParameter('APP_DATABASE_USER', 'php-course-app');
        $password = self::getEnvParameter('APP_DATABASE_PASSWORD', 'gX5t2UUbBn');
        return new \PDO($dsn, $user, $password);
    }

    private static function getEnvParameter(string $name, string $defaultValue): string
    {
        if ($value = getenv($name, true))
        {
            return $value;
        }
        return $defaultValue;
    }
}
