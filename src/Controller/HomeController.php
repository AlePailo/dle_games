<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\FranchiseRepository;
use PDO;

final class HomeController {
    
    private FranchiseRepository $franchiseRepository;

    public function __construct(PDO $pdo) {
        $this->franchiseRepository = new FranchiseRepository($pdo);
    }

    public function getAllFranchises() {
        $franchisesList = $this->franchiseRepository->getAll();

        dd($franchisesList);

    }
}