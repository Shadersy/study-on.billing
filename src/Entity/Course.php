<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $symbolCode;

    /**
     * @ORM\Column(type="smallint")
     */
    private $courseType;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cost;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbolCode(): ?string
    {
        return $this->symbolCode;
    }

    public function setSymbolCode(string $symbolCode): self
    {
        $this->symbolCode = $symbolCode;

        return $this;
    }

    public function getCourseType(): ?int
    {
        return $this->courseType;
    }

    public function setCourseType(int $courseType): self
    {
        $this->courseType = $courseType;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
