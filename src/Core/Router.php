<?php declare(strict_types = 1);

namespace App\Core;

use App\Controller\GameController;
use App\Controller\HomeController;

final class Router {

    private \PDO $pdo;
    private string $basePath;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
        $this->basePath = dirname($_SERVER['SCRIPT_NAME']);
    }

    public function dispatch(string $uri) : void {
        $path = parse_url($uri, PHP_URL_PATH);

        if($this->basePath !== '' && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        $path = trim($path, '/');

        if($path === '') {
            (new HomeController($this->pdo))->getAllFranchises();
            return;
        }
        
        (new GameController($this->pdo))->show($path);
    }

}