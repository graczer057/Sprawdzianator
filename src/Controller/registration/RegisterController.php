<?php

namespace App\Controller\registration;

use App\Entity\Users;
use App\Form\RegisterFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RegisterController extends AbstractController
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
     * @Route("/register", name="register")
     */
    public function register(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $existingUser = $this->usersRepository->findOneBy(['email' => $formData['email']]);

            if(!$existingUser){
                if($formData['1Password'] == $formData['2Password']){
                    $user = new Users(
                        $formData['email'],
                        $formData['2Password'],
                        $formData['name'],
                        $formData['surname'],
                        $formData['roles']
                    );

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $url = $this->generateUrl('app_activate_active', array('token' => $user->getToken()), UrlGenerator::ABSOLUTE_URL);

                    $email = (new Email())
                        ->from('sprawdzianator@gamil.com')
                        ->to($user->getEmail())
                        ->subject('Aktywuj swoje konto na portalu sprawdzianator.')
                        ->html('Link aktywacyjny: '.$url);

                    $mailer->send($email);

                    $this->addFlash('success', 'Twoje konto zostało założone, sprawdź swój adres mailowy w celu aktywacji konta.');

                    return $this->redirectToRoute('homepage');
                }else{
                    $this->addFlash('error', 'Hasła się nie zgadzają');

                    return $this->render('registration/registration.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }else{
                $this->addFlash('error', 'Użytkownik z podanym adresem email już istnieje');

                return $this->render('registration/registration.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}