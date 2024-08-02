<?php

namespace App;

use PDO;

class UserRegistration {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $password, $confirmPassword) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return ['error' => 'Invalid email format!'];
        }

        $pass = sha1($password);
        $cpass = sha1($confirmPassword);

        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ['error' => 'Email already exists!'];
        }

        if ($pass !== $cpass) {
            return ['error' => 'Confirm password not matched!'];
        }

        $stmt = $this->conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $cpass]);

        return ['success' => 'Registered successfully, login now please!'];
    }
}
