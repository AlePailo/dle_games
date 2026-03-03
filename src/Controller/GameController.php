<?php declare(strict_types = 1);

namespace App\Controller;

use App\Model\Repository\CharacterRepository;
use App\Model\Repository\FranchiseRepository;
use PDO;

final class GameController {

    private CharacterRepository $characterRepository;
    private FranchiseRepository $franchiseRepository;
    private string $basePath;

    public function __construct(PDO $pdo, string $basePath) {
        $this->characterRepository = new CharacterRepository($pdo);
        $this->franchiseRepository = new FranchiseRepository($pdo);
        $this->basePath = $basePath;
    }

    public function show($path) {

        // Load targeted franchise
        $franchise = $this->franchiseRepository->findBySlug($path);

        /* ----------------------------- INSERT CHECK IF ATTRIBUTE DEFINITIONS ARE LOADED ------------------------------------ */

        // Load 404 page if franchise not found or inactive
        if(!$franchise || !$franchise->getIsActive()) {
            $title = 'Page Not Found - DLE Games';
            ob_start();
            require __DIR__ . '/../View/404.php';
            $content = ob_get_clean();

            require __DIR__ . '/../View/layouts/main.php';

            return;
        }

        // Dynamic page details
        $title = $franchise->getName() . ' - DLE Games';
        $metaDescription = $franchise->getDescription();
        $gameBackground = $franchise->getBgImageUrl();
        $pageType = 'Game';


        // Prepare data that will be passed to frontend for game logic purpose
        $characters = $this->characterRepository->findByFranchiseId($franchise->getId());   // Get all targeted franchise characters
        $basePath = $this->basePath;
        $slug = $franchise->getSlug();

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

        $charactersForGame = [];
        foreach($characters as $character) {
            $data = [];

            foreach($columns as $field) {
                if(array_key_exists($field['key'], $character->getAttributes())) {
                    $data[$field['key']] = $character->getAttributes()[$field['key']];
                } else {
                    $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field['key'])));
                    if(method_exists($character, $getter)) {
                        $data[$field['key']] = $character->$getter();
                    } 
                }
            }

            $charactersForGame[$character->getName()] = $data;
        }

        ob_start();
        require __DIR__ . '/../View/game.php';
        $content = ob_get_clean();

        require __DIR__ . '/../View/layouts/main.php';
    }

    /*public function injectInMainLayout(string $filePath) : void {
        ob_start();
        require __DIR__ . $filePath;
        $content = ob_get_clean();

        require __DIR__ . '/../View/layouts/main.php';
    }*/

}