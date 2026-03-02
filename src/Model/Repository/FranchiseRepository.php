<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Franchise;
use App\Model\Entity\AttributeDefinitions;

class FranchiseRepository {
    
    public function __construct(private PDO $pdo) {}

    /*public function getAll() : array {
        $stmt = $this->pdo->prepare("SELECT * FROM franchises ORDER BY active DESC, name");
        $stmt->execute();

        $franchises = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $franchises[] = new Franchise((int)$row['id'], $row['name'], $row['slug'], $row['description'], (bool)$row['active'], $row['icon_url'], $row['bg_image_url'], $row['created_at'], $row['updated_at']);
        }

        return $franchises;
    }*/

    /*public function findBySlug($slug) : ?object {
        $stmt = $this->pdo->prepare("SELECT * FROM franchises WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }

        $franchiseObj = new Franchise((int)$res['id'], $res['name'], $res['slug'], $res['description'], (bool)$res['active'], $res['icon_url'], $res['bg_image_url'], $res['created_at'], $res['updated_at']);

        return $franchiseObj;
    }*/

    public function findBySlug(string $slug) : ?Franchise {
        $stmt = $this->pdo->prepare("SELECT * FROM franchises WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);

        $franchiseRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$franchiseRow) {
            return null;
        }

        

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