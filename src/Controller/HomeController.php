<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\FranchiseRepository;
use PDO;

final class HomeController extends BaseController {
    
    private FranchiseRepository $franchiseRepository;

    public function __construct(PDO $pdo, string $basePath) {
        parent::__construct($basePath);
        $this->franchiseRepository = new FranchiseRepository($pdo);
    }

    public function getAllFranchises() : void {
        $franchisesList = $this->franchiseRepository->getAll();

        $this->render('home', [
            'title' => 'Home - DLE Games',
            'metaDescription' => 'Guess characters from your favourite games and anime',
            'pageType' => 'Home',
            'franchisesList' => $franchisesList
        ]);
    }
}