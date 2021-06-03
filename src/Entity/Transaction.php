<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class)
     */
    private $course;

    /**
     * @ORM\Column(type="smallint")
     */
    private $operationType;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(name="end_of_rent", type="datetime")
     * @ORM\JoinColumn(nullable=false)
     */
    public $endOfRent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @ORM\JoinColumn(nullable=true)
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }
    

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getOperationType(): ?int
    {
        return $this->operationType;
    }

    public function setOperationType(int $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getEndOfRent(): ?\DateTimeInterface
    {
        return $this->endOfRent;
    }

    public function setEndOfRent(\DateTimeInterface $endOfRent): self
    {
        $this->endOfRent = $endOfRent;

        return $this;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
