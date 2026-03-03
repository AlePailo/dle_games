<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class Character {

    private string $name;
    private string $imageUrl;
    private array $attributes;

    
    public function __construct(
        //int $id,
        string $name,
        string $imageUrl,
        array $attributes
    ) {
        //$this->id = $id;
        $this->name = $name;
        $this->imageUrl = $imageUrl;
        $this->attributes = $attributes;
    }

    /*public function getId() : int {
        return $this->id;
    }*/

    public function getName() : string {
        return $this->name;
    }

    public function getImageUrl() : string {
        return $this->imageUrl;
    }

    public function getAttributes() : array {
        return $this->attributes;
    }

    public function getAttribute(string $key) : string {
        return $this->attributes[$key] ?? null;
    }

    public function hasAttribute(string $key) : bool {
        return array_key_exists($key, $this->attributes);
    }
}