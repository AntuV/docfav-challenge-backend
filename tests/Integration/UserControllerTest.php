<?php

namespace Tests\Integration;

use App\Controllers\UserController;
use Tests\BaseUserTest;

class UserControllerTest extends BaseUserTest
{
    const USER_FIRST_NAME = 'Pablo';
    const USER_LAST_NAME = 'Gonzalez';
    const USER_EMAIL = 'pablo.gonzalez@gmail.com';
    const USER_PASSWORD = '12345678';

    public function setUp(): void
    {
        $_POST = [];
    }

    public function testPost()
    {
        $_POST['first_name'] = self::USER_FIRST_NAME;
        $_POST['last_name'] = self::USER_LAST_NAME;
        $_POST['email'] = self::USER_EMAIL;
        $_POST['password'] = self::USER_PASSWORD;

        $controller = new UserController();

        $response = $controller->post();

        $this->assertIsString($response);

        $user = json_decode($response, true);

        $this->assertIsArray($user);

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('last_name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('created_at', $user);

        $this->assertEquals(self::USER_FIRST_NAME, $user['first_name']);
        $this->assertEquals(self::USER_LAST_NAME, $user['last_name']);
        $this->assertEquals(self::USER_EMAIL, $user['email']);
        $date = date_create_from_format('Y-m-d H:i:s', $user['created_at']);
        $this->assertEquals(date_create()->format('Y-m-d'), $date->format('Y-m-d'));

        $_POST = [];
    }

    public function testPostInvalid()
    {
        $_POST['first_name'] = self::USER_FIRST_NAME;
        $_POST['last_name'] = self::USER_LAST_NAME;
        $_POST['email'] = self::USER_EMAIL;
        // Not sending password

        $controller = new UserController();

        $result = $controller->post();

        $this->assertIsString($result);

        $response = json_decode($result, true);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('message', $response);

        $this->assertEquals('Missing required fields: password', $response['message']);

        $_POST = [];
    }

    /**
     * @depends testPost
     */
    public function testGet()
    {
        $controller = new UserController();

        $response = $controller->get();

        $this->assertIsString($response);

        $users = json_decode($response, true);

        $this->assertIsArray($users);
        $this->assertArrayHasKey('id', $users[0]);
        $this->assertArrayHasKey('first_name', $users[0]);
        $this->assertArrayHasKey('last_name', $users[0]);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertArrayHasKey('created_at', $users[0]);

        $this->assertEquals(1, $users[0]['id']);
        $this->assertEquals(self::USER_FIRST_NAME, $users[0]['first_name']);
        $this->assertEquals(self::USER_LAST_NAME, $users[0]['last_name']);
        $this->assertEquals(self::USER_EMAIL, $users[0]['email']);
        $date = date_create_from_format('Y-m-d H:i:s', $users[0]['created_at']);
        $this->assertEquals(date_create()->format('Y-m-d'), $date->format('Y-m-d'));
    }
}
