<?php declare(strict_types = 1);

namespace App\Model\Repository;

use PDO;
use App\Model\Entity\Franchise;

class FranchiseRepository {
    
    public function __construct(private PDO $pdo) {}

    public function getAll() : array {
        $stmt = $this->pdo->prepare("SELECT * FROM franchises ORDER BY active DESC, name");
        $stmt->execute();

        $franchises = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $franchises[] = new Franchise((int)$row['id'], $row['name'], $row['slug'], $row['description'], (bool)$row['active'], $row['icon_url'], $row['bg_image_url'], $row['created_at'], $row['updated_at']);
        }

        return $franchises;
    }

    public function findBySlug($slug) : ?object {
        $stmt = $this->pdo->prepare("SELECT * FROM franchises WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$res) {
            return null;
        }

        $franchiseObj = new Franchise((int)$res['id'], $res['name'], $res['slug'], $res['description'], (bool)$res['active'], $res['icon_url'], $res['bg_image_url'], $res['created_at'], $res['updated_at']);

        return $franchiseObj;
    }

}