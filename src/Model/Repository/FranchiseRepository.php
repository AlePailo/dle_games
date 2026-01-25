<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Franchise;

class FranchiseRepository {
    
    public function __construct(private PDO $pdo) {}

    public function getAll() : array {
        $stmt = $this->pdo->prepare("SELECT id, name, slug, description, active FROM franchises");
        $stmt->execute();

        $franchises = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $franchises[] = new Franchise((int)$row['id'], $row['name'], $row['slug'], $row['description'], (bool)$row['active']);
        }

        return $franchises;
    }

}