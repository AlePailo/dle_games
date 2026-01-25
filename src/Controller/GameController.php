<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\CharacterRepository;
use PDO;

final class GameController {

    private CharacterRepository $characterRepository;

    public function __construct(PDO $pdo) {
        $this->characterRepository = new CharacterRepository($pdo);
    }

    public function show($path) {
        $charactersList = $this->characterRepository->findByFranchiseSlug($path);

        dd($charactersList);
    }

}