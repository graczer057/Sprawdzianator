<?php

namespace App\Controller\workspace\student;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student/workspace/{organisation}")
 */

class studentWorkspaceController extends AbstractController
{
    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}", name="student_org_workspace")
     */
    public function landing(string $organisation, string $classToken){
        return $this->render('workspace/student/studentWorkspace.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken
        ]);
    }
}