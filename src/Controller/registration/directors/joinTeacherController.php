<?php

namespace App\Controller\registration\directors;

use App\Entity\Teachers;
use App\Repository\DirectorsRepository;
use App\Repository\OrganisationsRepository;
use App\Repository\TeachersRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class joinTeacherController extends AbstractController
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
     * @Route("/director/join/{id}", name="director_join")
     */
    public function join(int $id, UserInterface $user){
        $uTeacher = $this->usersRepository->findOneBy(['id' => $id]);
        $director = $this->directorsRepository->findOneBy(['User' => $user->getId()]);
        $organisation = $this->organisationsRepository->findOneBy(['id' => $director->getOrganization()->getId()]);

        $teacher = new Teachers(
            $uTeacher,
            $director,
            $organisation
        );

        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        $this->addFlash('success', 'Udało się dodać nauczyciela do twojej organizacji!');
        return $this->redirectToRoute('director_landing', ['workspace_name' => $organisation->getToken()]);
    }
}