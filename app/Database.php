<?php

namespace App;

use PDO;

class Database
{
    private static string $path = __DIR__ . '/../database/database.sqlite';
    private static string $testingPath = __DIR__ . '/../database/database.testing.sqlite';

    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {
        if ($this->isTesting()) {
            $this->connection = new PDO('sqlite:' . self::$testingPath);
        } else {
            $this->connection = new PDO('sqlite:' . self::$path);
        }

        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
            self::$instance->migrate();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function migrate()
    {
        $this->connection->exec('CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )');
    }

    private function isTesting(): bool
    {
        return defined('PHPUNIT_ENV');
    }
}
