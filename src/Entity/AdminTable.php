<?php

namespace App\Entity;

use App\Repository\AdminTableRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\UserTable;
/**
 * @ORM\Entity(repositoryClass=AdminTableRepository::class)
 */
class AdminTable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $idAdmin;

    /**
     * @ORM\Column(type="string", length=45, name="nameAdmin")
     */
    private $nameAdmin;

    /**
     * @ORM\Column(type="string", length=50, name="emailAdmin")
     */
    private $emailAdmin;

    /**
        * @ORM\Column(type="string", name="telephoneAdmin")
     * @var 
     */
    private $telephoneAdmin;

    public function getId(): ?int
    {
        return $this->idAdmin;
    }

    public function getNameAdmin(): ?string
    {
        return $this->nameAdmin;
    }
    public function setNameAdmin(?string $nameAdmin): self
    {
        $this->nameAdmin = $nameAdmin;

        return $this;
    }

    public function getEmailAdmin(): ?string
    {
        return $this->emailAdmin;
    }

    public function setEmailAdmin(?string $emailAdmin): self 
    {
        $this->emailAdmin = $emailAdmin;
        return $this;
    }

    public function getTelephoneAdmin(): ?string
    {
        return $this->telephoneAdmin;
    }

    public function setTelephoneAdmin(string $telephoneAdmin): self
    {
        $this->telephoneAdmin = $telephoneAdmin;

        return $this;
    }   
}
