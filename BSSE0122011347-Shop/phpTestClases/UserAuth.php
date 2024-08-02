<?php

namespace App;

use PDO;

class UserAuth {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            return ['error' => 'Invalid email format!'];
        }

        $pass = sha1($password);

        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
        $stmt->execute([$email, $pass]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return ['success' => $user['id']];
        } else {
            return ['error' => 'Incorrect username or password!'];
        }
    }
}
