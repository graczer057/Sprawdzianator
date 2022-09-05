<?php

namespace App\Controller\teachers\withOrg;

use App\Form\teachers\withOrg\teacherFindFormType;
use App\Repository\DirectorsRepository;
use App\Repository\OrganisationsRepository;
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

class findOrgController extends AbstractController
{
    private $entityManager;
    private $usersRepository;
    private $teachersRepository;
    private $organisationsRepository;
    private $directorsRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        TeachersRepository $teachersRepository,
        OrganisationsRepository $organisationsRepository,
        DirectorsRepository $directorsRepository
    ){
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
        $this->teachersRepository = $teachersRepository;
        $this->organisationsRepository = $organisationsRepository;
        $this->directorsRepository = $directorsRepository;
    }

    /**
     * @Route("/teacher/find/organisation", name="teacher_find")
     */
    public function find(Request $request, MailerInterface $mailer, UserInterface $user){
        $form = $this->createForm(teacherFindFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $org = $this->organisationsRepository->findOneBy(['name' => $data['org']->getName()]);
            $orgId = $org->getId();

            $director = $this->directorsRepository->findOneBy(['Organisation' => $orgId]);
            $directorId = $director->getUser();

            $uDirector = $this->usersRepository->findOneBy(['id' => $directorId]);
            $directorEmail = $uDirector->getEmail();

            $url = $this->generateUrl('director_join', array('id' => $user->getId()), UrlGenerator::ABSOLUTE_URL);

            $email = (new Email())
                ->from('sprawdzianator@gmail.com')
                ->to($directorEmail)
                ->subject('Użytkownik '.$user->getName().' '.$user->getSurname().' wysyła prośbę o dołączenie do organizacji.')
                ->html('W celu zatwierdzenia prośby prosimy o uruchomienie załączonego linka: '.$url);

            $mailer->send($email);

            $this->addFlash('success', 'Prośba o dołączenie do organizacji została wysłana pomyślnie.');

            $this->redirectToRoute('homepage');
        }

        return $this->render('teachers/withOrg/teacherFind.html.twig', [
            'form' => $form->createView()
        ]);
    }
}