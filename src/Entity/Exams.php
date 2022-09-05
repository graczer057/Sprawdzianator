<?php

namespace App\Entity;

use App\Repository\ExamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExamsRepository::class)
 */
class Exams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Teachers::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    /**
     * @ORM\ManyToOne(targetEntity=Organisations::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $activeDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    public function __construct(
        Teachers $teacher,
        Organisations $organisation,
        Classes $class,
        string $title,
        string $subject,
        ?\DateTime $activeDate
    ){
        $this->teacher = $teacher;
        $this->organisation = $organisation;
        $this->class = $class;
        $this->title = $title;
        $this->subject = $subject;
        $this->isActive = 0;
        $this->activeDate = $activeDate;
        $this->createDate = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teachers
    {
        return $this->teacher;
    }

    public function setTeacher(?Teachers $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getOrganisation(): ?Organisations
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisations $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function getClass(): ?Classes
    {
        return $this->class;
    }

    public function setClass(?Classes $class): self
    {
        $this->class = $class;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getActiveDate(): ?\DateTimeInterface
    {
        return $this->activeDate;
    }

    public function setActiveDate(\DateTimeInterface $activeDate): self
    {
        $this->activeDate = $activeDate;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
