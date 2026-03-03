<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class Franchise {

    private int $id;
    private string $name;
    private string $slug;
    private string $description;
    private bool $isActive;
    private string $icon_url;
    private string $bg_image_url;
    private string $created_at;     //gestire meglio il type
    private string $updated_at;     //gestire meglio il type
    private array $attributeDefinitions;

    public function __construct(
        int $id,
        string $name,
        string $slug,
        string $description,
        bool $isActive,
        string $icon_url,
        string $bg_image_url,
        string $created_at,
        string $updated_at,
        array $attributeDefinitions = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->isActive = $isActive;
        $this->icon_url = $icon_url;
        $this->bg_image_url = $bg_image_url;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->attributeDefinitions = $attributeDefinitions;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getSlug() : string {
        return $this->slug;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getIsActive() : bool {
        return $this->isActive;
    }

    public function getIconUrl() : string {
        return $this->icon_url;
    }

    public function getBgImageUrl() : string {
        return $this->bg_image_url;
    }

    public function getCreatedAt() : string {
        return $this->created_at;
    }

    public function getUpdatedAt() : string {
        return $this->updated_at;
    }

    public function getAttributeDefinitions() : array {
        return $this->attributeDefinitions;
    }

    public function hasAttributeDefinitions() : bool {
        return !empty($this->attributeDefinitions);
    }

    public function isNew($days = 30) : bool {
        $created_at = new \DateTime($this->created_at);
        $threshold =  new \DateTime(" -{$days} days");

        return $created_at >= $threshold;
    }

}