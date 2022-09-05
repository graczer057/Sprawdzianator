<?php

namespace App\Entity;

use App\Repository\StudentExamsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentExamsRepository::class)
 */
class StudentExams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Students::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Exams::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $exam;

    /**
     * @ORM\ManyToOne(targetEntity=Excersises::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $exercises;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;

    public function __construct(
        Students $student,
        Exams $exam
    ){
        $this->student = $student;
        $this->exam = $exam;
        $this->isDone = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Students
    {
        return $this->student;
    }

    public function setStudent(?Students $student): self
    {
        $this->student = $student;

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

    public function getExercises(): ?Excersises
    {
        return $this->exercises;
    }

    public function setExercises(?Excersises $exercises): self
    {
        $this->exercises = $exercises;

        return $this;
    }

    public function getIsDone(): ?bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }
}
