<?php

namespace App\Controller\registration\directors;

use App\Repository\DirectorsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class directorController extends AbstractController
{
    private $directorsRepository;

    public function __construct(
        DirectorsRepository $directorsRepository
    ){
      $this->directorsRepository = $directorsRepository;
    }

    /**
     * @IsGranted("ROLE_DIRECTOR")
     * @Route("/director/complete", name="director_complete")
     */
    public function complete(UserInterface $user){
        $id = $user->getId();

        if($user->getCompleted() == 0){
            return $this->redirectToRoute("organisation_complete");
        }else{
            $org = $this->directorsRepository->findOneBy(['User' => $id])->getOrganization()->getToken();
            return $this->redirectToRoute('director_landing', ['organisation' => $org]);
        }
    }
}