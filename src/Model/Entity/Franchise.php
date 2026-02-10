<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class Franchise {

    private int $id;
    private string $name;
    private string $slug;
    private string $description;
    private bool $active;
    private string $icon_url;
    private string $bg_image_url;
    private string $created_at;     //gestire meglio il type
    private string $updated_at;     //gestire meglio il type

    public function __construct(
        int $id,
        string $name,
        string $slug,
        string $description,
        bool $active,
        string $icon_url,
        string $bg_image_url,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->active = $active;
        $this->icon_url = $icon_url;
        $this->bg_image_url = $bg_image_url;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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

    public function getIconUrl() {
        return $this->icon_url;
    }

    public function getBgImageUrl() {
        return $this->bg_image_url;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getUpdatedAt() {
        return $this->updated_at;
    }

}