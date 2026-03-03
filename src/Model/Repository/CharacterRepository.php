<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Character;

class CharacterRepository {

    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findByFranchiseId(int $franchiseId) : array {
        // Fetch all attributes values in db related to the targeted franchise and pair them with their attributes key and the character they relate to
        $stmt = $this->pdo->prepare("SELECT c.id, c.name, c.image_url, ad.attribute_key, ca.value
                                        FROM characters c
                                        JOIN character_attributes ca ON ca.character_id = c.id
                                        JOIN attribute_definitions ad ON ad.id = ca.attribute_definition_id
                                        WHERE c.franchise_id = :franchise_id
                                        ORDER BY ad.display_order");
        $stmt->execute(['franchise_id' => $franchiseId]);

        /*
        Fill the characters array to shape data in a format ready for the Character object creation

        For each fetched row the code checks if the id field is already registered as a key in the array (if it's not an entry is created with the id field as the key)
        then the attribute is properly inserted in the attributes subarray of the correct character
        */
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

        // Returning an array of characters objects 
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