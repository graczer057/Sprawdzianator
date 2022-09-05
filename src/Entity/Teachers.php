<?php

namespace App\Entity;

use App\Repository\TeachersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeachersRepository::class)
 */
class Teachers
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
     * @ORM\ManyToOne(targetEntity=Directors::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $Director;

    /**
     * @ORM\OneToOne(targetEntity=Organisations::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Organisation;

    public function __construct(
        Users $user,
        Directors $directors,
        Organisations $organisations
    ){
        $this->User = $user;
        $this->Director = $directors;
        $this->Organisation = $organisations;
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

    public function getDirector(): ?Directors
    {
        return $this->Director;
    }

    public function setDirector(?Directors $Director): self
    {
        $this->Director = $Director;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganisation()
    {
        return $this->Organisation;
    }

    /**
     * @param mixed $Organisation
     */
    public function setOrganisation($Organisation): void
    {
        $this->Organisation = $Organisation;
    }
}
