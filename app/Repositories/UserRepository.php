<?php

namespace App\Repositories;

use App\Exceptions\UserNotCreatedException;
use App\Exceptions\UserNotDeletedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotUpdatedException;
use App\Models\User;
use PDOException;

class UserRepository extends BaseRepository
{
    /**
     * Create a new user
     *
     * The user's ID is set automatically
     *
     * @param User $user
     * @throws UserNotCreatedException
     */
    public function create(User $user): void
    {
        if ($user->getId()) {
            throw new UserNotCreatedException('The user already has an ID');
        }

        $sql = 'INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (:first_name, :last_name, :email, :password, :created_at)';

        $params = [
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
        ];

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            $user->setId((int)$this->connection->lastInsertId());
        } catch (PDOException $e) {
            throw new UserNotCreatedException($e->getMessage());
        }
    }

    /**
     * Update an existing user
     *
     * @param User $user
     * @throws UserNotUpdatedException
     * @throws UserNotFoundException
     */
    public function update(User $user): void
    {
        $sql = 'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, password = :password WHERE id = :id';

        $params = [
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ];

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            if (!$statement->rowCount()) {
                throw new UserNotFoundException();
            }
        } catch (PDOException $e) {
            throw new UserNotUpdatedException($e->getMessage());
        }
    }

    /**
     * Delete an existing user
     *
     * @throws UserNotDeletedException
     * @throws UserNotFoundException
     */
    public function delete(User $user): void
    {
        $sql = 'DELETE FROM users WHERE id = :id';

        $params = [
            'id' => $user->getId(),
        ];

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            if (!$statement->rowCount()) {
                throw new UserNotFoundException();
            }
        } catch (PDOException $e) {
            throw new UserNotDeletedException($e->getMessage());
        }
    }

    /**
     * Find a user by ID
     *
     * @throws UserNotFoundException
     */
    public function findByIdOrFail(int $id): ?User
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $params = [
            'id' => $id,
        ];

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetch();

        if (!$result) {
            throw new UserNotFoundException();
        }

        return new User(
            (int)$result['id'],
            $result['first_name'],
            $result['last_name'],
            $result['email'],
            $result['password'],
            date_create_from_format('Y-m-d H:i:s', $result['created_at'])
        );
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = 'SELECT * FROM users';

        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();

        $users = [];
        foreach ($results as $result) {
            $users[] = new User(
                (int)$result['id'],
                $result['first_name'],
                $result['last_name'],
                $result['email'],
                $result['password'],
                date_create_from_format('Y-m-d H:i:s', $result['created_at'])
            );
        }

        return $users;
    }
}
