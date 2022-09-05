<?php

namespace App\Controller\registration\teachers;

use App\Entity\Users;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class teacherController extends AbstractController
{
    /**
     * @IsGranted("ROLE_TEACHER")
     * @Route("/teacher/complete", name="teacher_complete")
     */
    public function complete(UserInterface $user){
        if($user->getCompleted() == 0){
            return $this->redirectToRoute("organisation_complete");
        }else{
           return $this->render('workspace/teacher/teacherWorkspace.html.twig');
        }
    }
}