<?php declare(strict_types = 1);

namespace App\Model\Entity;

final class Character {

    //private int $id;
    private string $name;
    private string $gender;
    private string $age;
    private string $hairColor;
    private string $eyesColor;
    private string $affiliation;
    private string $role;
    private string $status;
    private string $imageUrl;

    
    public function __construct(
        //int $id,
        string $name,
        string $gender,
        string $age,
        string $hairColor,
        string $eyesColor,
        string $affiliation,
        string $role,
        string $status,
        string $imageUrl
    ) {
        //$this->id = $id;
        $this->name = $name;
        $this->gender = $gender;
        $this->age = $age;
        $this->hairColor = $hairColor;
        $this->eyesColor = $eyesColor;
        $this->affiliation = $affiliation;
        $this->role = $role;
        $this->status = $status;
        $this->imageUrl = $imageUrl;
    }

    /*public function getId() : int {
        return $this->id;
    }*/

    public function getName() : string {
        return $this->name;
    }

    public function getGender() : string {
        return $this->gender;
    }

    public function getAge() : string {
        return $this->age;
    }

    public function getHairColor() : string {
        return $this->hairColor;
    }

    public function getEyesColor() : string {
        return $this->eyesColor;
    }

    public function getAffiliation() : string {
        return $this->affiliation;
    }

    public function getRole() : string {
        return $this->role;
    }

    public function getStatus() : string {
        return $this->status;
    }

    public function getImageUrl() : string {
        return $this->imageUrl;
    }

}