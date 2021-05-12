<?php

namespace App\Entity;

use App\Repository\ExcersisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExcersisesRepository::class)
 */
class Excersises
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Grade;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Excersises;

    /**
     * @ORM\Column(type="integer")
     */
    private $Group;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="Excersises")
     */
    private $Users;

    public function __construct()
    {
        $this->Users = new ArrayCollection();
    }

    public function getId(int $id): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?int
    {
        return $this->Grade;
    }

    public function setGrade(int $Grade): self
    {
        $this->Grade = $Grade;

        return $this;
    }

    public function getExcersises(): ?string
    {
        return $this->Excersises;
    }

    public function setExcersises(string $Excersises): self
    {
        $this->Excersises = $Excersises;

        return $this;
    }

    public function getGroup(): ?int
    {
        return $this->Group;
    }

    public function setGroup(int $Group): self
    {
        $this->Grade = $Group;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
            $user->setExcersises($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->Users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getExcersises() === $this) {
                $user->setExcersises(null);
            }
        }

        return $this;
    }
}
