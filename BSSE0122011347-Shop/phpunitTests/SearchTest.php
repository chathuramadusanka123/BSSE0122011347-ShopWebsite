<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Search;
use PDO;
use PDOStatement;

class SearchTest extends TestCase {
    private $pdo;
    private $search;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->search = new Search($this->pdo);
    }

    public function testSearchProductsFound() {
        $searchBox = 'ProductName'; // Adjust this to match test data

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([
            [
                'id' => 1,
                'name' => 'ProductName',
                'price' => '100',
                'image_01' => 'product-image.jpg'
            ]
        ]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $results = $this->search->search($searchBox);
        $this->assertCount(1, $results); // Ensure one result is found
        $this->assertEquals('ProductName', $results[0]['name']);
    }

    public function testSearchNoProductsFound() {
        $searchBox = 'NonExistentProduct';

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn([]);
        $this->pdo->method('prepare')->willReturn($stmt);

        $results = $this->search->search($searchBox);
        $this->assertCount(0, $results); // Ensure no results are found
    }
}
