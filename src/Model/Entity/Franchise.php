<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class Franchise {

    private int $id;
    private string $name;
    private string $slug;
    private string $description;
    private bool $active;

    public function __construct(
        int $id,
        string $name,
        string $slug,
        string $description,
        bool $active
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->active = $active;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getActive() {
        return $this->active;
    }

}