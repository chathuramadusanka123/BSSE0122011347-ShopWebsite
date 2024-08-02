<?php

namespace App;

use PDO;

class Search {
    private $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function search($searchBox) {
        // Sanitize the search input
        $searchBox = filter_var($searchBox, FILTER_SANITIZE_STRING);

        // Prepare and execute the search query
        $stmt = $this->conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
        $stmt->execute(["%{$searchBox}%"]);

        // Fetch and return the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
