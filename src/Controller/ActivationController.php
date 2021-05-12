<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ExpireFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ActivationController extends AbstractController
{
    private $entityManager;
    private $usersRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository
    ){
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
    }

    /**
     * @Route("/activate/{token}", name="app_activate_active")
     */

    public function activate(string $token, Request $request, MailerInterface $mailerInterface){
        $date = new \DateTime("now");

        $user = $this->usersRepository->findOneBy(['token' => $token]);

        $form = $this->createForm(ExpireFormType::class, $user);
        $form->handleRequest($request);

        if(is_null($user)){
            return $this->render('base.html.twig');
        }

        if($user->getTokenExpire()->getTimestamp() > $date->getTimestamp()){
            $user->activation();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $email = (new Email())
                ->from('bartlomiej.szyszkowski@yellows.eu')
                ->to($user->getEmail())
                ->subject('Congrats! You are registered on our website!')
                ->text('Welcome, dear User. We glad you join our family of ToDo Project. On this page you can easily start making some of your tasks in dedicated time. Have a nice day!');

            $mailerInterface->send($email);

            $this->addFlash('success', 'User Activated');

            return $this->redirectToRoute('homepage');

        }else{
            return $this->redirectToRoute('expire');
        }
    }
}