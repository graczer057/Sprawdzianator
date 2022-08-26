<?php

namespace App\Controller\students;

use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class studentHomepage extends AbstractController
{

    private $usersRepository;
    private $entityManager;

    public function __construct(
        UsersRepository $usersRepository,
        entityManagerInterface $entityManager
    ){
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/student/homepage", name="studentHomepage")
     */
    public function studentHomepage(){


        return $this->render('students/studentHomepage.html.twig');
    }
}