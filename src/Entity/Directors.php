<?php

namespace App\Entity;

use App\Repository\DirectorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DirectorsRepository::class)
 */
class Directors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\OneToOne(targetEntity=Organisations::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Organisation;

    public function __construct(
        Users $user,
        Organisations $organisation
    ){
        $this->User = $user;
        $this->Organisation = $organisation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Users
    {
        return $this->User;
    }

    public function setUser(Users $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getOrganization(): ?Organisations
    {
        return $this->Organisation;
    }

    public function setOrganization(Organisations $Organization): self
    {
        $this->Organisation = $Organization;

        return $this;
    }
}
