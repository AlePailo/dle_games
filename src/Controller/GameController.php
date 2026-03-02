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
        $franchise = $this->franchiseRepository->findBySlug($path);
        $basePath = $this->basePath;
        $pageType = 'Game';

        if(!$franchise || /*!$franchise->getActive()*/ !$franchise->getIsActive()) {
            $title = 'Page Not Found - DLE Games';
            ob_start();
            require __DIR__ . '/../View/404.php';
            $content = ob_get_clean();
            require __DIR__ . '/../View/layouts/main.php';

            return;
        }

        /*if(!$franchise->getActive()) {
            ob_start();
            require __DIR__ . '/../View/comingSoon.php';
            $content = ob_get_clean();
            require __DIR__ . '/../View/layouts/main.php';

            return;
        }*/

        $characters = $this->characterRepository->findByFranchiseId($franchise->getId());

        $title = $franchise->getName() . ' - DLE Games';
        $metaDescription = $franchise->getDescription();
        $slug = $franchise->getSlug();
        $gameBackground = $franchise->getBgImageUrl();
        
        /*$config = require __DIR__ . '/../../config/games.php';
        $gameConfig = [$config[$franchise->getSlug()]] ?? null;
        if(!$gameConfig) {
            throw new NotFoundException();
        }
        $columns = $gameConfig[0]['columns'];
        */

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
            /*foreach(array_keys($columns) as $field) {
                $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
                if (method_exists($character, $getter)) {
                    $data[$field] = $character->$getter();
                }
            }*/

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

}