<?php

namespace App\Entity;

use App\Repository\StudentPicturesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentPicturesRepository::class)
 */
class StudentPictures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=StudentExams::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $studentExams;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    public function __construct(
        string $picture,
        StudentExams $studentExams
    ){
        $this->picture = $picture;
        $this->studentExams = $studentExams;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentExams(): ?StudentExams
    {
        return $this->studentExams;
    }

    public function setStudentExams(?StudentExams $studentExams): self
    {
        $this->studentExams = $studentExams;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
