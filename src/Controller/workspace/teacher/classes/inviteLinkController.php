<?php

namespace App\Controller\workspace\teacher\classes;

use App\Entity\Students;
use App\Entity\Users;
use App\Form\workspace\teacher\classes\studentRegisterFormType;
use App\Repository\ClassesRepository;
use App\Repository\OrganisationsRepository;
use App\Repository\StudentsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class inviteLinkController extends AbstractController
{
    private $entityManager;
    private $usersRepository;
    private $classesRepository;
    private $organisationsRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        ClassesRepository $classesRepository,
        OrganisationsRepository $organisationsRepository
    ){
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
        $this->classesRepository = $classesRepository;
        $this->organisationsRepository = $organisationsRepository;
    }

    /**
     *
     * @Route("/register/teammate/{classId}/{organisation}", name="register_student")
     */
    public function inviteLink(Request $request, MailerInterface $mailer, int $classId, string $organisation){
        $form = $this->createForm(studentRegisterFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $existingUser = $this->usersRepository->findOneBy(['email' => $data['email']]);

            if(!$existingUser){
                if($data['1Password'] == $data['2Password']){
                    $class = $this->classesRepository->findOneBy(['id' => $classId]);
                    $organisationObject = $this->organisationsRepository->findOneBy(['token' => $organisation]);

                    $user = new Users(
                        $data['email'],
                        $data['2Password'],
                        $data['name'],
                        $data['surname'],
                        "ROLE_STUDENT"
                    );

                    $user->setCompleted(1);

                    $this->entityManager->persist($user);

                    $student = new Students(
                        $user,
                        $organisationObject,
                        $class
                    );

                    $this->entityManager->persist($student);
                    $this->entityManager->flush();

                    $url = $this->generateUrl('app_activate_active', array('token' => $user->getToken()), UrlGenerator::ABSOLUTE_URL);
                    $email = (new Email())
                        ->from('sprawdzianator@gmail.com')
                        ->to($user->getEmail())
                        ->subject('Aktywuj swoje konto na portalu sprawdzianator.')
                        ->html('Link aktywacyjny: '.$url);

                    $mailer->send($email);

                    $this->addFlash('success', 'Twoje konto zostało założone, sprawdź swój adres mailowy w celu aktywacji konta.');

                    return $this->redirectToRoute('homepage');
                }else{
                    $this->addFlash('error', 'Hasła się nie zgadzają');

                    return $this->render('registration/students/studentRegistration.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }else{
                $this->addFlash('error', 'Użytkownik z podanym adresem email już istnieje');

                return $this->render('registration/students/studentRegistration.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }
        return $this->render('registration/students/studentRegistration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}