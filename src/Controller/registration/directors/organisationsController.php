<?php

namespace App\Controller\registration\directors;

use App\Entity\Directors;
use App\Entity\Organisations;
use App\Form\registration\organisation\organisationFormType;
use App\Repository\DirectorsRepository;
use App\Repository\OrganisationsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class organisationsController extends AbstractController
{
    private $entityManager;
    private $organisationsRepository;
    private $usersRepository;
    private $directorsRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrganisationsRepository $organisationsRepository,
        UsersRepository $usersRepository,
        DirectorsRepository $directorsRepository
    ){
        $this->entityManager = $entityManager;
        $this->organisationsRepository = $organisationsRepository;
        $this->usersRepository = $usersRepository;
        $this->directorsRepository = $directorsRepository;
    }

    /**
     * @IsGranted("ROLE_DIRECTOR")
     *
     * @Route("/organisations/complete", name="organisation_complete")
     */
    public function organisation(UserInterface $users, Request $request, MailerInterface $mailer){
        $form = $this->createForm(organisationFormType::class);
        $form->handleRequest($request);

        $id = $users->getId();

        $user = $this->usersRepository->findOneBy(['id' => $id]);

        if(($form->isSubmitted()) && ($form->isValid())){
            $data = $form->getData();

            $existingOrganisation = $this->organisationsRepository->findOneBy(['name' => $data['name']]);

            $existingDirector = $this->directorsRepository->findBy(['User' => $id]);

            if(!$existingOrganisation && !$existingDirector){
                $organisation = new Organisations(
                    $data['name']
                );

                $this->entityManager->persist($organisation);
                $this->entityManager->flush();

                $director = new Directors(
                    $user,
                    $organisation
                );

                $this->entityManager->persist($director);
                $this->entityManager->flush();

                $completed = $user->setCompleted(1);

                $this->entityManager->persist($completed);
                $this->entityManager->flush();

                $email = (new Email())
                    ->from('sprawdzianator@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Organizacja została zarejestrowana pomyślnie.')
                    ->html('Gratulacje użytkowniku! Udało się Tobie pomyślnie zarejestrować swoją szkołę na portalu sprawdzianator.');

                $mailer->send($email);

                $this->addFlash('success', 'Organizacja poprawnie założona.');

                return $this->redirectToRoute('director_landing', ['organisation' => $organisation->getToken()]);
            }else{
                $this->addFlash('error', 'Podana organizacja już istnieje.');

                return $this->render('registration/organisations.html.twig', [
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('registration/organisations.html.twig', [
            'form' => $form->createView()
        ]);
    }
}