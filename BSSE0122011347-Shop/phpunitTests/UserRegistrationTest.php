<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\UserRegistration;
use PDO;
use PDOStatement;

class UserRegistrationTest extends TestCase {
    private $pdo;
    private $userRegistration;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->userRegistration = new UserRegistration($this->pdo);
    }

    public function testSuccessfulRegistration() {
        $name = 'John Doe';
        $email = 'john@example.com';
        $password = 'password123';
        $confirmPassword = 'password123';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(0); // No existing users
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->userRegistration->register($name, $email, $password, $confirmPassword);
        $this->assertEquals(['success' => 'Registered successfully, login now please!'], $result);
    }

    public function testEmailAlreadyExists() {
        $name = 'Jane Doe';
        $email = 'jane@example.com';
        $password = 'password123';
        $confirmPassword = 'password123';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(1); // Email exists
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->userRegistration->register($name, $email, $password, $confirmPassword);
        $this->assertEquals(['error' => 'Email already exists!'], $result);
    }

    public function testPasswordMismatch() {
        $name = 'Alice';
        $email = 'alice@example.com';
        $password = 'password123';
        $confirmPassword = 'differentpassword';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(0); // No existing users
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->userRegistration->register($name, $email, $password, $confirmPassword);
        $this->assertEquals(['error' => 'Confirm password not matched!'], $result);
    }

    public function testInvalidEmailFormat() {
        $name = 'Bob';
        $email = 'invalidemail';
        $password = 'password123';
        $confirmPassword = 'password123';

        $result = $this->userRegistration->register($name, $email, $password, $confirmPassword);
        $this->assertEquals(['error' => 'Invalid email format!'], $result);
    }
}
