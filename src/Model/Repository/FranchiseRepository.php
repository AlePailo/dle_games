<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Franchise;
use App\Model\Entity\AttributeDefinitions;

class FranchiseRepository {
    
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() : array {
        // Fetching all the franchises
        $stmt = $this->pdo->prepare("SELECT * FROM franchises ORDER BY is_active DESC, name");
        $stmt->execute();

        // Building array of franchises objects
        // AttributeDefinitions not provided (auto initialized in class file) to avoid fetching the attributeDefinitions table in db (useless for this function purpose) 
        $franchises = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $franchises[] = new Franchise((int)$row['id'], $row['name'], $row['slug'], $row['description'], (bool)$row['is_active'], $row['icon_url'], $row['bg_image_url'], $row['created_at'], $row['updated_at']);
        }

        return $franchises;
    }

    public function findBySlug(string $slug) : ?Franchise {

        // Targeted franchise fetch
        $stmt = $this->pdo->prepare("SELECT * FROM franchises WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);

        $franchiseRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$franchiseRow) {
            return null;
        }

        
        // Franchise's attribute definitions fetch
        $stmt = $this->pdo->prepare("SELECT attribute_key, attribute_label, display_order
                                    FROM attribute_definitions
                                    WHERE franchise_id = ?
                                    ORDER BY display_order ASC");
        $stmt->execute([$franchiseRow['id']]);
        
        $definitionsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $attributeDefinitions = [];
        foreach($definitionsRows as $row) {
            $attributeDefinitions[] = new AttributeDefinitions(
                $row['attribute_key'],
                $row['attribute_label'],
                (int) $row['display_order']
            );
        }

        // Franchise Object construction
        return new Franchise(
            (int) $franchiseRow['id'],
            $franchiseRow['name'],
            $franchiseRow['slug'],
            $franchiseRow['description'],
            (bool) $franchiseRow['is_active'],
            $franchiseRow['icon_url'],
            $franchiseRow['bg_image_url'],
            $franchiseRow['created_at'],
            $franchiseRow['updated_at'],
            $attributeDefinitions
        );
    }

}