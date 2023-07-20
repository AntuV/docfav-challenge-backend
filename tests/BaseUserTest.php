<?php

namespace Tests;

use App\Database;
use App\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class BaseUserTest extends TestCase
{
    protected UserRepository $repository;

    public static function setUpBeforeClass(): void
    {
        $database = Database::getInstance();
        $connection = $database->getConnection();

        $connection->exec('DROP TABLE IF EXISTS users');

        $database->migrate();
    }

    public function setUp(): void
    {
        $this->repository = new UserRepository();
    }
}
