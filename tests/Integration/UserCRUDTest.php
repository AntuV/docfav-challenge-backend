<?php

namespace Tests\Integration;

use App\Exceptions\UserNotCreatedException;
use App\Exceptions\UserNotDeletedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotUpdatedException;
use App\Models\User;
use Tests\BaseUserTest;

class UserCRUDTest extends BaseUserTest
{
    /**
     * @throws UserNotCreatedException|UserNotFoundException
     */
    public function testCreateNewUser()
    {
        $user = new User(null, 'Pablo', 'Gonzalez', 'pablo.gonzalez@gmail.com', password_hash('12345678', PASSWORD_BCRYPT));
        $this->repository->create($user);

        // Check it was created
        $this->assertEquals(1, $user->getId(), 'The created user ID should be 1');

        $new_user = $this->repository->findByIdOrFail(1);

        // Check the database record matches the intended one
        $this->assertEquals($user->getFirstName(), $new_user->getFirstName(), 'The first name should be Pablo');
        $this->assertEquals($user->getLastName(), $new_user->getLastName(), 'The last name should be Gonzalez');
        $this->assertEquals($user->getEmail(), $new_user->getEmail(), 'The email should be pablo.gonzalez@gmail.com');
        $this->assertTrue(password_verify('12345678', $new_user->getPassword()), 'The password should be 12345678');
        $this->assertEquals($user->getCreatedAt()->format('Y-m-d H:i:s'), $new_user->getCreatedAt()->format('Y-m-d H:i:s'), 'The created_at should be the same');
    }

    /**
     * @throws UserNotCreatedException
     */
    public function testCreateExistingUserError()
    {
        $this->expectException(UserNotCreatedException::class);

        $user = new User(1, 'Pablo', 'Gonzalez', 'pablo.gonzalez@gmail.com', password_hash('12345678', PASSWORD_BCRYPT));

        $this->repository->create($user);
    }

    /**
     * @depends testCreateNewUser
     * @throws UserNotFoundException|UserNotUpdatedException
     */
    public function testUpdateExistingUser()
    {
        $user = $this->repository->findByIdOrFail(1);

        $this->assertTrue($user !== null, 'The user should exist');

        $user->setFirstName('Pedro');
        $user->setLastName('Perez');
        $user->setEmail('pedro.perez@gmail.com');
        $user->setPassword('87654321');

        $this->repository->update($user);

        $updated_user = $this->repository->findByIdOrFail(1);

        $this->assertEquals($user->getFirstName(), $updated_user->getFirstName(), 'The first name should be Pedro');
        $this->assertEquals($user->getLastName(), $updated_user->getLastName(), 'The last name should be Perez');
        $this->assertEquals($user->getEmail(), $updated_user->getEmail(), 'The email should be pedro.perez@gmail.com');
        $this->assertTrue(password_verify('87654321', $updated_user->getPassword()), 'The password should be 87654321');
        $this->assertEquals($user->getCreatedAt()->format('Y-m-d H:i:s'), $updated_user->getCreatedAt()->format('Y-m-d H:i:s'), 'The created_at should be the same');
    }

    /**
     * @throws UserNotUpdatedException
     */
    public function testUpdateNonExistingUserError()
    {
        $this->expectException(UserNotFoundException::class);

        $user = new User(2, 'Pablo', 'Gonzalez', 'pablo.gonzalez@gmail.com', password_hash('12345678', PASSWORD_BCRYPT));

        $this->repository->update($user);
    }

    /**
     * @depends testCreateNewUser
     * @depends testUpdateExistingUser
     * @throws UserNotFoundException|UserNotDeletedException
     */
    public function testDelete()
    {
        $user = $this->repository->findByIdOrFail(1);

        $this->assertTrue($user !== null, 'The user should exist');

        $this->repository->delete($user);

        $this->expectException(UserNotFoundException::class);

        $this->repository->findByIdOrFail(1);
    }

    /**
     * @throws UserNotDeletedException
     */
    public function testDeleteNonExistingUserError()
    {
        $this->expectException(UserNotFoundException::class);

        $user = new User(2, 'Pablo', 'Gonzalez', 'pablo.gonzalez@gmail.com', password_hash('12345678', PASSWORD_BCRYPT));

        $this->repository->delete($user);
    }
}
