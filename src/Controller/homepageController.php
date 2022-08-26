<?php

namespace App\Controller;

use App\Entity\Excersises;
use App\Entity\Users;
use App\Form\NewTasksFormType;
use App\Form\PasswordFormType;
use App\Form\UploadFormType;
use App\Repository\ExcersisesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class homepageController extends AbstractController
{
    private $entityManager;
    private $usersRepository;
    private $excersisesRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        ExcersisesRepository $excersisesRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
        $this->excersisesRepository = $excersisesRepository;
    }

    /**
     * @Route("", name="homepage")
     */
    public function homepage()
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @Route("/student/add", name="student_add")
     */
    public function AddStudent(Request $request)
    {
        $form = $this->createForm(NewTasksFormType::class);
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid())) {
            $formData = $form->getData();

            $surname = $this->usersRepository->findOneBy(['surname' => $formData['surname'], 'name' => $formData['name']]);

            if(!$surname){
                switch($formData['grade']){
                    case 3:
                        $group = rand(1, 2);
                        $object = $this->excersisesRepository->findOneBy(['Grade' => 3, 'Group' => $group]);
                        break;

                    case 4:
                        $group = rand(1, 2);
                        $object = $this->excersisesRepository->findOneBy(['Grade' => 4, 'Group' => $group]);
                        break;

                    case 5:
                        $group = rand(1, 2);
                        $object = $this->excersisesRepository->findOneBy(['Grade' => 5, 'Group' => $group]);
                        break;

                    case 6:
                        $group = rand(1, 2);
                        $object = $this->excersisesRepository->findOneBy(['Grade' => 6, 'Group' => $group]);
                        break;
                }

                //$newUser = new Users(
                    //$formData['name'],
                    //$formData['surname'],
                    //$object
                //);



                //$this->entityManager->persist($newUser);
                $this->entityManager->flush();

                //$users = $this->usersRepository->find($newUser);

                return $this->redirectToRoute('test', [
                    'users' => $surname,
                    'token' => $surname->getToken()
                ]);
            }
        }else{
            $this->addFlash('notice', "Niestety, ale zadania na podane imię i nazwisko zostały odebrane");
        }
        return $this->render('AddStudent.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{token}/excersises", name="test")
     */
    public function Excersises(string $token, Request $request){

        $form = $this->createForm(UploadFormType::class);
        $form->handleRequest($request);

        $users = $this->usersRepository->findOneBy(['Token' => $token]);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();

            $destination = $this->getParameter('kernel.project_dir').'/public/assets/uploads';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );

            $photo = $users->setImageFilename($newFilename);

            $this->entityManager->persist($photo);
            $this->entityManager->flush();

        }
        return $this->render('excersises.html.twig', [
            'form' => $form->createView(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/report", name="report")
     */
    public function Report(Request $request){
        $form = $this->createForm(PasswordFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $form['password']->getData();

            $truePassword = "SPRAWDZIAN_3TI_ZAQ!2wsx";

            if($password == $truePassword){
                $users = $this->usersRepository->findAll();

                return $this->render('document.html.twig', [
                    'users' => $users
                ]);
            }
        }

        return $this->render('question.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/report/download", name="download")
     */
    public function Download(){
        $users = $this->usersRepository->findAll();
        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->render('document.html.twig', [
            'users' => $users
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream('3ti_report.pdf', [
            "Attachment" => true
        ]);

        return $this->render('document.html.twig', [
            'users' => $users
        ]);
    }
}