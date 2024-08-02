<?php

namespace App;

use PDO;

class ContactForm {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function sendMessage($name, $email, $number, $msg) {
        // Sanitize inputs
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $msg = filter_var($msg, FILTER_SANITIZE_STRING);

        // Check if the message already exists
        $stmt = $this->conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
        $stmt->execute([$name, $email, $number, $msg]);

        if ($stmt->rowCount() > 0) {
            return ['error' => 'already sent message!'];
        }

        // Insert the message
        $stmt = $this->conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        $user_id = ''; // Example user_id or fetch it from session if available
        $stmt->execute([$user_id, $name, $email, $number, $msg]);

        return ['success' => 'sent message successfully!'];
    }
}
