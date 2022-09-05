<?php

namespace App\Controller\workspace\director;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/director/workspace/{organisation}")
 */

class landingDWorkspace extends AbstractController
{
    /**
     * @IsGranted("ROLE_DIRECTOR", subject="organisation")
     *
     * @Route(name="director_landing")
     */
    public function landing(string $organisation){

        return $this->render('workspace/director/directorWorkspace.html.twig');
    }
}