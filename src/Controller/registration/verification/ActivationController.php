<?php

namespace App\Controller\registration\verification;

use App\Form\ExpireFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ActivationController extends AbstractController
{
    private $entityManager;
    private $usersRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository        $usersRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
    }

    /**
     * @Route("/activate/{token}", name="app_activate_active")
     */
    public function activate(string $token, Request $request, MailerInterface $mailerInterface)
    {
        $date = new \DateTime("now");

        $user = $this->usersRepository->findOneBy(['token' => $token]);

        $form = $this->createForm(ExpireFormType::class, $user);
        $form->handleRequest($request);

        if (is_null($user)) {
            return $this->render('landing/homepage.html.twig');
        }

        if ($user->getTokenExpire()->getTimestamp() > $date->getTimestamp()) {
                $user->activation();

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $email = (new Email())
                    ->from('sprawdzianator@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Gratulacje, aktywacja konta przebiegła pomyślnie!')
                    ->text('Witaj użytkowniku. Z wielką przyjemnością informujemy Ciebie, że aktywacja konta przebiegła pomyślnie. Prosimy o uzupełnienie dodatkowych informacji na stronie.');

                $mailerInterface->send($email);

                $this->addFlash('success', 'Użytkownik pomyślnie aktywowany');

                return $this->redirectToRoute('app_login');
        } else {
            return $this->redirectToRoute('expire', [
                'token' => $token
            ]);
        }
    }
}