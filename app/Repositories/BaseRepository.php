<?php

namespace App\Repositories;

use App\Database;
use PDO;

class BaseRepository
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }
}
