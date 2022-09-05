<?php

namespace App\Controller\workspace\teacher\classes;

use App\Entity\Classes;
use App\Form\workspace\teacher\classes\addClassFormType;
use App\Form\workspace\teacher\classes\editClassFormType;
use App\Repository\ClassesRepository;
use App\Repository\OrganisationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @Route("teacher/workspace/{organisation}/classes")
 */

class classesCRUDController extends AbstractController
{
    private $organisationsRepository;
    private $classesRepository;
    private $entityManager;

    public function __construct(
        OrganisationsRepository $organisationsRepository,
        ClassesRepository $classesRepository,
        EntityManagerInterface $entityManager
    ){
        $this->organisationsRepository = $organisationsRepository;
        $this->classesRepository = $classesRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/add", name="teacher_add_class")
     */
    public function addClasses(string $organisation, Request $request){
        $form = $this->createForm(addClassFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $organisationObject = $this->organisationsRepository->findOneBy(['token' => $organisation]);

            $existingClass = $this->classesRepository->findBy(['organsiation' => $organisationObject, 'name' => $data['name']]);

            if(!$existingClass){
                $link = 'temporary link';

                $class = new Classes(
                    $data['name'],
                    $link,
                    $organisationObject
                );

                $this->entityManager->persist($class);
                $this->entityManager->flush();

                $newLink = $this->generateUrl('register_student', array('classId' => $class->getId(), 'organisation' => $organisation), UrlGenerator::ABSOLUTE_URL);

                $class->setLink($newLink);

                $this->entityManager->persist($class);
                $this->entityManager->flush();

                $this->addFlash('success', 'Udało sie dodać klasę do twojej organizacji');

                return $this->redirectToRoute('teacher_org_workspace', ['organisation' => $organisation]);
            }else{
                $this->addFlash('error', 'Klasa o podanej nazwie już istnieje w tej organizacji.');

                return $this->render('workspace/teacher/classes/addClasses.html.twig', [
                    'form' => $form->createView(),
                    'organisation' => $organisation
                ]);
            }
        }
        return $this->render('workspace/teacher/classes/addClasses.html.twig', [
            'form' => $form->createView(),
            'organisation' => $organisation
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/list", name="teacher_list_classes")
     */
    public function listClasses(string $organisation){
        $classes = $this->classesRepository->findAll();

        return $this->render('workspace/teacher/classes/listClasses.html.twig', [
            'classes' => $classes,
            'organisation' => $organisation
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/edit/{classId}", name="teacher_edit_class")
     */
    public function editClass(string $organisation, int $classId, Request $request){
        $class = $this->classesRepository->findOneBy(['id' => $classId]);

        if(!$class){
            $this->addFlash('error', 'Przykro nam, ale podana klasa nie istnieje.');

            return $this->redirectToRoute('teacher_list_classes', ['organisation' => $organisation]);
        }else{
            $form = $this->createForm(editClassFormType::class, $class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $this->entityManager->persist($class);
                $this->entityManager->flush();

                $this->addFlash('success', 'Klasa została poprawnie edytowana');

                return $this->redirectToRoute('teacher_list_classes', ['organisation' => $organisation]);
            }

            return $this->render('workspace/teacher/classes/editClasses.html.twig', [
                'form' => $form->createView(),
                'organisation' => $organisation
            ]);
        }
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/delete/{classId}", name="teacher_delete_class")
     */
    public function deleteClass(string $organisation, int $classId){
        $class = $this->classesRepository->findOneBy(['id' => $classId]);

        if(!$class){
            $this->addFlash('error', 'Przykro nam, ale ta klasa już nie istnieje');

            return $this->redirectToRoute('teacher_list_classes', ['organisation' => $organisation]);
        }else{
            $this->entityManager->remove($class);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się usunąć klasę');

            return $this->redirectToRoute('teacher_list_classes', ['organisation' => $organisation]);
        }
    }
}