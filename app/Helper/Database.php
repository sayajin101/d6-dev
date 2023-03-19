<?php

namespace App\Helper;

final class Database
{
    protected static \PDO|null $instance = null;
    private static array $credentials = [
        'host' => 'localhost',
        'database' => 'd6',
        'username' => 'd6',
        'password' => '',
    ];

    public function __construct()
    {
        // 
    }

    /**
     * Connect to the database and return the connection
     */
    public static function connect() : \PDO
    {
        if (self::$instance === null) {
            $credentials = self::getCredentials();

            $dsn = "mysql:host={$credentials['host']};dbname={$credentials['database']}";

            self::$instance = new \PDO($dsn, $credentials['username'], $credentials['password']);
        }

        return self::$instance;
    }

    /**
     * Get the database configuration
     */
    private static function getCredentials() : array
    {
        return self::$credentials;
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}
}
