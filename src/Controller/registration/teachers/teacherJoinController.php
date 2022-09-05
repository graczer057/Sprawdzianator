<?php

namespace App\Controller\registration\teachers;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class teacherJoinController extends AbstractController
{
    /**
     * @Route("/join/organisation/{workspace}", name="teacher_join")
     */
    public function teacherJoin(string $workspace){
        dd($workspace);
    }
}