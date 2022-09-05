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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $excersises;

    /**
     * @ORM\Column(type="integer")
     */
    private $exerciseGroup;

    /**
     * @ORM\ManyToOne(targetEntity=Exams::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exam;

    public function __construct(
        int $grade,
        string $excersises,
        int $exerciseGroup,
        Exams $exam
    ){
        $this->grade = $grade;
        $this->excersises = $excersises;
        $this->exerciseGroup = $exerciseGroup;
        $this->exam = $exam;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getExcersises(): ?string
    {
        return $this->excersises;
    }

    public function setExcersises(string $excersises): self
    {
        $this->excersises = $excersises;

        return $this;
    }

    public function getExam(): ?Exams
    {
        return $this->exam;
    }

    public function setExam(?Exams $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getExerciseGroup(): ?int
    {
        return $this->exerciseGroup;
    }

    public function setExerciseGroup(int $exerciseGroup): self
    {
        $this->exerciseGroup = $exerciseGroup;

        return $this;
    }
}
