<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\UserAuth; // Ensure the namespace is correct
use PDO;
use PDOStatement;

class UserAuthTest extends TestCase {
    private $pdo;
    private $userAuth;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->userAuth = new UserAuth($this->pdo);
    }

    public function testLoginSuccess() {
        $email = 'chathura@gmail.com';
        $password = '123456';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn(['id' => 1]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->userAuth->login($email, $password);
        $this->assertEquals(['success' => 1], $result);
    }

    public function testLoginFailure() {
        $email = 'chathura@example.com';
        $password = 'wrongpassword';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn(false);
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->userAuth->login($email, $password);
        $this->assertEquals(['error' => 'Incorrect username or password!'], $result);
    }

    public function testInvalidEmailFormat() {
        $result = $this->userAuth->login('invalidemail', 'password123');
        $this->assertEquals(['error' => 'Invalid email format!'], $result);
    }
}
