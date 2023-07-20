<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\BaseUserTest;

class UserTest extends BaseUserTest
{
    private User $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User(1, 'Pablo', 'Gonzalez', 'pablo.gonzalez@gmail.com', password_hash('12345678', PASSWORD_BCRYPT));
    }

    public function testGetId()
    {
        $this->assertEquals(1, $this->user->getId(), 'The created user ID should be 1');
    }

    public function testSetId()
    {
        $this->user->setId(2);
        $this->assertEquals(2, $this->user->getId(), 'The created user ID should now be 2');
    }

    public function testGetFirstName()
    {
        $this->assertEquals('Pablo', $this->user->getFirstName(), 'The first name should be Pablo');
    }

    public function testSetFirstName()
    {
        $this->user->setFirstName('Pedro');
        $this->assertEquals('Pedro', $this->user->getFirstName(), 'The first name should now be Pedro');
    }

    public function testGetLastName()
    {
        $this->assertEquals('Gonzalez', $this->user->getLastName(), 'The last name should be Gonzalez');
    }

    public function testSetLastName()
    {
        $this->user->setLastName('Perez');
        $this->assertEquals('Perez', $this->user->getLastName(), 'The last name should now be Perez');
    }

    public function testGetEmail()
    {
        $this->assertEquals('pablo.gonzalez@gmail.com', $this->user->getEmail(), 'The email should be pablo.gonzalez@gmail.com');
    }

    public function testSetEmail()
    {
        $this->user->setEmail('pedro.perez@gmail.com');
        $this->assertEquals('pedro.perez@gmail.com', $this->user->getEmail(), 'The email should now be pedro.perez@gmail.com');
    }

    public function testGetPassword()
    {
        $this->assertTrue(password_verify('12345678', $this->user->getPassword()), 'The password should be 12345678');
    }

    public function testSetPassword()
    {
        $this->user->setPassword('87654321');
        $this->assertTrue(password_verify('87654321', $this->user->getPassword()), 'The password should now be 87654321');
    }

    public function testGetCreatedAt()
    {
        $this->assertEquals(date_create()->format('Y-m-d'), $this->user->getCreatedAt()->format('Y-m-d'), 'The created_at should be today');
    }
}
