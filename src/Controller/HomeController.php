<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\FranchiseRepository;
use PDO;

final class HomeController {
    
    private FranchiseRepository $franchiseRepository;

    public function __construct(PDO $pdo, string $basePath) {
        $this->franchiseRepository = new FranchiseRepository($pdo);
        $this->basePath = $basePath;
    }

    public function getAllFranchises() {
        $franchisesList = $this->franchiseRepository->getAll();

        $basePath = $this->basePath;
        $title = 'Home - DLE Games';
        $metaDescription = 'Guess characters from your favourite games and anime';

        ob_start();
        require __DIR__ . '/../View/home.php';
        $content = ob_get_clean();

        require __DIR__ . '/../View/layouts/main.php';

    }
}