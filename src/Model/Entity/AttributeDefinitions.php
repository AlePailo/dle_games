<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class AttributeDefinitions {
    private string $key;
    private string $label;
    private int $displayOrder;

    public function __construct(
        string $key,
        string $label,
        int $displayOrder
    ) {
        $this->key = $key;
        $this->label = $label;
        $this->displayOrder = $displayOrder;
    }

    public function getKey() {
        return $this->key;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getDisplayOrder() {
        return $this->displayOrder;
    }
}