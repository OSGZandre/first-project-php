<?php

namespace App\Entity;

use App\Repository\UserTableRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTableRepository::class)
 * @ORM\Table(name="userTable")
 */
class UserTable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @var 
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="telephoneNumber")
     */
    private $telephoneNumber;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->userName;
    }

    public function setName(string $userName): self
    {
        $this->name = $userName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber(string $telephoneNumber): self
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }
}