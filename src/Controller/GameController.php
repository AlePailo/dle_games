<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\CharacterRepository;
use App\Model\Repository\FranchiseRepository;
use App\Model\Entity\Franchise;
use PDO;

final class GameController extends BaseController {

    private CharacterRepository $characterRepository;
    private FranchiseRepository $franchiseRepository;

    public function __construct(PDO $pdo, string $basePath) {
        parent::__construct($basePath);
        $this->characterRepository = new CharacterRepository($pdo);
        $this->franchiseRepository = new FranchiseRepository($pdo);
    }


    public function show(string $path) : void {
        // Load targeted franchise
        $franchise = $this->franchiseRepository->findBySlug($path);

        if(!$franchise || !$franchise->getIsActive()) {
            $this->render404();
            return;
        }

        $this->renderGame($franchise);
    }    


    private function render404() : void {
        $title = 'Page Not Found - DLE Games';
        ob_start();
        require __DIR__ . '/../View/404.php';
        $content = ob_get_clean();

        require __DIR__ . '/../View/layouts/main.php';
    }

    private function renderGame(Franchise $franchise) : void {
        $columns = $this->buildColumns($franchise);
        $characters = $this->characterRepository->findByFranchiseId($franchise->getId());
        $this->render('game', [
            'title' => $franchise->getName(),
            'metaDescription' => $franchise->getDescription(),
            'gameBackground' => $franchise->getBgImageUrl(),
            'pageType' => 'Game',
            'columns' => $this->buildColumns($franchise),
            'charactersForGame' => $this->buildCharactersData($characters, $columns),
            'slug' => $franchise->getSlug()
        ]);
    }

    private function buildColumns(Franchise $franchise) : array {
        $columns = [
            ['key' => 'image_url', 'label' => 'Image'],
            ['key' => 'name', 'label' => 'Name']
        ];
        foreach($franchise->getAttributeDefinitions() as $def) {
            $columns[] = [
                'key' => $def->getKey(),
                'label' => $def->getLabel()
            ];
        }

        return $columns;
    }

    private function buildCharactersData(array $characters, array $columns) : array {
        $result = [];

        foreach ($characters as $character) {
            $data = [];
            foreach ($columns as $field) {
                $key = $field['key'];
                if($character->hasAttribute($key)) {
                    $data[$key] = $character->getAttribute($key);
                } elseif($key === 'name') {
                    $data[$key] = $character->getName();
                } elseif($key === 'image_url') {
                    $data[$key] = $character->getImageUrl();
                }
            }
            $result[$character->getName()] = $data;
        }

        return $result;
    }
}