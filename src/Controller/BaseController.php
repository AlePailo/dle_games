<?php declare(strict_types = 1);

namespace App\Controller;

abstract class BaseController {

    protected string $basePath;

    public function __construct(string $basePath) {
        $this->basePath = $basePath;
    }

    protected function render(string $view, array $data = []): void {
        extract($data); // trasforma le chiavi dell'array in variabili
        $basePath = $this->basePath;

        ob_start();
        require __DIR__ . '/../View/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../View/layouts/main.php';
    }
}