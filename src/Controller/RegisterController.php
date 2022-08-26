<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);

        if (($form->isSubmitted() && $form->isValid())) {
            $formData = $form->getData();

            $existingUser = $this->usersRepository->findOneBy(['email' => $formData['email']]);

            if($formData['1Password'] == $formData['2Password']){
                if(!$existingUser){
                    $user = new Users(
                        $formData['email'],
                        $formData['1Password'],
                        $formData['name'],
                        $formData['surname'],
                        $formData['roles']
                    );

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $url = $this->generateUrl('app_activate_active', array('token' => $user->getToken()), UrlGenerator::ABSOLUTE_URL);

                    $email = (new Email())
                        ->from('bartlomiej.szyszkowski@yellows.eu')
                        ->to($user->getEmail())
                        ->subject('Activate your account')
                        ->html($url);

                    $mailer->send($email);

                    $this->addFlash('success', 'Twoje konto zostało założone, sprawdź swój adres mailowy w celu aktywacji konta.');

                    return $this->redirectToRoute('homepage');
                }else{
                    $this->addFlash('error', 'Użytkownik z podanym adresem email już istnieje');

                    return $this->render('registration.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }else{

                $this->addFlash('error', 'Hasła się nie zgadzają');

                return $this->render('registration.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}