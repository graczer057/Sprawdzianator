<?php

namespace App\Controller\registration\students;

use App\Repository\ExamsRepository;
use App\Repository\StudentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class studentController extends AbstractController
{
    private $examsRepository;
    private $studentsRepository;
    private $entityRepository;

    public function __construct(
        ExamsRepository $examsRepository,
        StudentsRepository $studentsRepository,
        EntityManagerInterface $entityManager
    ){
        $this->examsRepository = $examsRepository;
        $this->studentsRepository = $studentsRepository;
        $this->entityRepository = $entityManager;
    }

    /**
     * @IsGranted("ROLE_STUDENT")
     * @Route("/student/complete", name="student_complete")
     */
    public function complete(UserInterface $user){
        $completed = $user->getCompleted();

        $id = $user->getId();

        if($completed == 0){
            return $this->render('workspace/error.html.twig');
        }else{
            $student = $this->studentsRepository->findOneBy(['user' => $id]);

            $studentOrg = $student->getOrganisation()->getToken();

            $class = $student->getClass()->getToken();

            return $this->redirectToRoute('student_org_workspace', ['organisation' => $studentOrg, 'classToken' => $class]);
        }
    }
}