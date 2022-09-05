<?php

namespace App\Controller\workspace\director;

use App\Form\workspace\director\addTeacherFormType;
use App\Repository\DirectorsRepository;
use App\Repository\TeachersRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

class addTeacherController extends AbstractController
{
    private $teachersRepository;
    private $directorsRepository;
    private $usersRepository;
    private $entityManager;

    public function __construct(
        TeachersRepository $teachersRepository,
        DirectorsRepository $directorsRepository,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager
    ){
        $this->teachersRepository = $teachersRepository;
        $this->directorsRepository = $directorsRepository;
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/director/add/teacher", name="add_teacher")
     */
    public function addTeacher(Request $request, MailerInterface $mailer, UserInterface $user){
        $form = $this->createForm(addTeacherFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $id = $user->getId();
            $director = $this->directorsRepository->findOneBy(['User' => $id]);

            $directorId = $director->getId();
            $organisation = $director->getOrganization();
            $organisationId = $organisation->getId();
            $organisationToken = $organisation->getToken();

            $existingUser = $this->usersRepository->findOneBy(['email' => $data['email']]);

            if(!$existingUser){
                $url = $this->generateUrl('teacher_join', array('workspace' => $organisationToken), UrlGenerator::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('sprawdzianator@gmail.com')
                    ->to($data['email'])
                    ->subject('Dyrektor twojej szkoły zaprasza Ciebie do skorzystania z portalu')
                    ->html($url);

                $mailer->send($email);

                $this->addFlash('success', 'Zaproszenie nauczyciela do organizacji przebiegło pomyślnie');
                return $this->redirectToRoute('director_landing', ['organisation' => $organisationToken]);

            }else{
                $this->addFlash('error', 'Przykro nam, ale podany adres email należy do zarejestrowanego już użytkownika.');

                return $this->render('workspace/director/addTeacher.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }
        return $this->render('workspace/director/addTeacher.html.twig', [
            'form' => $form->createView()
        ]);
    }
}