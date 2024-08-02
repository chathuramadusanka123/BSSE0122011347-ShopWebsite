<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\ContactForm; // Adjust the namespace as necessary
use PDO;
use PDOStatement;


class ContactFormTest extends TestCase {
    private $pdo;
    private $contactForm;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->contactForm = new ContactForm($this->pdo);
    }

    public function testSendMessageSuccess() {
        $name = 'Chathura Jayathilaka';
        $email = 'chathura@gmail.com';
        $number = '1234567890';
        $msg = 'Hello, this is a test message.';

        // Mock the statement for checking if the message already exists
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(0); // Simulate no existing message
        $this->pdo->method('prepare')->willReturn($stmt);

        // Mock the statement for inserting a new message
        $insertStmt = $this->createMock(PDOStatement::class);
        $insertStmt->method('execute')->willReturn(true);
        $this->pdo->method('prepare')->willReturn($insertStmt);

        $result = $this->contactForm->sendMessage($name, $email, $number, $msg);
        $this->assertEquals(['success' => 'sent message successfully!'], $result);
    }

    public function testMessageAlreadyExists() {
        $name = 'Chathura Jayathilaka';
        $email = 'chathura@gmail.com';
        $number = '1234567890';
        $msg = 'Hello, this is a test message.';
        
        // Mock the statement for checking if the message already exists
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(1); // Simulate message already exists
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = $this->contactForm->sendMessage($name, $email, $number, $msg);
        $this->assertEquals(['error' => 'already sent message!'], $result);
    }
}
