<?php

namespace App\Controller\teachers;

use App\Repository\OrganisationsRepository;
use App\Repository\TeachersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class teacherChooseController extends AbstractController
{
    private $teachersRepository;

    public function __construct(
        TeachersRepository $teachersRepository
    ){
        $this->teachersRepository = $teachersRepository;
    }

    /**
     * @Route("/teacher/choose", name="teacher_choose")
     */
    public function choose(UserInterface $user){
        $completed = $user->getCompleted();
        $id = $user->getId();

        if($completed == 0){
            return $this->render('teachers/teacherChoose.html.twig');
        }else{
            $teacher = $this->teachersRepository->findOneBy(['User' => $id]);
            $teacherOrg = $teacher->getOrganisation()->getToken();
            return $this->redirectToRoute('teacher_org_workspace', ['organisation' => $teacherOrg]);
        }
    }
}