<?php

namespace App\Entity;

use App\Repository\ClassesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClassesRepository::class)
 */
class Classes
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity=Organisations::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organsiation;

    public function __construct(
        string $name,
        string $link,
        Organisations $organisations
    ){
        $this->name = $name;
        $this->link = $link;
        $this->token = md5(uniqid());
        $this->organsiation = $organisations;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOrgansiation(): ?Organisations
    {
        return $this->organsiation;
    }

    public function setOrgansiation(?Organisations $organsiation): self
    {
        $this->organsiation = $organsiation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
