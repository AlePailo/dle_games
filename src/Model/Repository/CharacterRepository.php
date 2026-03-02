<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Character;

class CharacterRepository {
    
    public function __construct(private PDO $pdo) {}

    /*public function findByFranchiseId(int $franchiseId) {
        $stmt = $this->pdo->prepare("SELECT * FROM characters WHERE franchise_id = :franchise_id");
        $stmt->execute(['franchise_id' => $franchiseId]);

        $characters = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $characters[] = new Character(//(int)$row['id'],
                $row['name'], $row['gender'], $row['age'], $row['hair_color'], $row['eyes_color'], $row['affiliation'], $row['role'], $row['status'], $row['image_url']);
        }

        return $characters;
    }*/

    public function findByFranchiseId(int $franchiseId) : array {
        $stmt = $this->pdo->prepare("SELECT c.id, c.name, c.image_url, ad.attribute_key, ca.value
                                        FROM characters c
                                        JOIN character_attributes ca ON ca.character_id = c.id
                                        JOIN attribute_definitions ad ON ad.id = ca.attribute_definition_id
                                        WHERE c.franchise_id = :franchise_id
                                        ORDER BY ad.display_order");
        $stmt->execute(['franchise_id' => $franchiseId]);

        $characters = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];

            if(!isset($characters[$id])) {
                $characters[$id] = [
                    'id' => $id,
                    'name' => $row['name'],
                    'image_url' => $row['image_url'],
                    'attributes' => []
                ];
            }

            $characters[$id]['attributes'][$row['attribute_key']] = $row['value'];
        }

        $entities = [];

        foreach($characters as $data) {
            $entities[] = new Character(
                $data['name'],
                $data['image_url'],
                $data['attributes']
            );
        }
        
        return $entities;
    } 

}