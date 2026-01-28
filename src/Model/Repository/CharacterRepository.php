<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Character;

class CharacterRepository {
    
    public function __construct(private PDO $pdo) {}

    public function findByFranchiseId(int $franchiseId) {
        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE franchise_id = :franchise_id");
        $stmt->execute(['franchise_id' => $franchiseId]);

        $characters = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $characters[] = new Character($row['name'], $row['gender'], $row['age'], $row['hair_color'], $row['eyes_color'], $row['affiliation'], $row['role'], $row['status'], $row['image_url']);
        }

        return $characters;
    }

}