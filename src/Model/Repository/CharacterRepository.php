<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Character;

class CharacterRepository {
    
    public function __construct(private PDO $pdo) {}

    public function findByFranchiseSlug(string $franchiseSlug) {
        $stmt = $this->pdo->prepare("SELECT c.name, gender, age, hair_color, eyes_color, affiliation, role, status, c.image_url 
            FROM characters c INNER JOIN franchises f ON c.franchise_id = f.id
            WHERE slug = :slug");
        $stmt->execute(['slug' => $franchiseSlug]);

        $characters = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $characters[] = new Character($row['name'], $row['gender'], $row['age'], $row['hair_color'], $row['eyes_color'], $row['affiliation'], $row['role'], $row['status'], $row['image_url']);
        }

        return $characters;
    }

}