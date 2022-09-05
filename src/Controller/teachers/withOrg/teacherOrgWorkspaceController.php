<?php

namespace App\Controller\teachers\withOrg;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("teacher/workspace/{organisation}")
 */

class teacherOrgWorkspaceController extends AbstractController
{
    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route(name="teacher_org_workspace")
     */
    public function landing(string $organisation){
        return $this->render('workspace/teacher/teacherWorkspace.html.twig', [
            'organisation' => $organisation
        ]);
    }
}