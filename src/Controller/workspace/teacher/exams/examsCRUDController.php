<?php

namespace App\Controller\workspace\teacher\exams;

use App\Entity\Exams;
use App\Entity\Excersises;
use App\Form\workspace\teacher\exams\createExamsFormType;
use App\Form\workspace\teacher\exams\editExamFormType;
use App\Form\workspace\teacher\exams\exercises\addExercisesFormType;
use App\Form\workspace\teacher\exams\exercises\editExercisesFormType;
use App\Repository\ExamsRepository;
use App\Repository\ExcersisesRepository;
use App\Repository\OrganisationsRepository;
use App\Repository\StudentExamsRepository;
use App\Repository\StudentPicturesRepository;
use App\Repository\StudentsRepository;
use App\Repository\TeachersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/teacher/workspace/{organisation}/exams")
 */

class examsCRUDController extends AbstractController
{
    private $organisationsRepository;
    private $entityManager;
    private $teachersRepository;
    private $examsRepository;
    private $excersisesRepository;
    private $studentsRepository;
    private $studentExamsRepository;
    private $studentPicturesRepository;

    public function __construct(
        OrganisationsRepository $organisationsRepository,
        EntityManagerInterface $entityManager,
        TeachersRepository $teachersRepository,
        ExamsRepository $examsRepository,
        ExcersisesRepository $excersisesRepository,
        StudentsRepository $studentsRepository,
        StudentExamsRepository $studentExamsRepository,
        StudentPicturesRepository $studentPicturesRepository
    ){
        $this->organisationsRepository = $organisationsRepository;
        $this->entityManager = $entityManager;
        $this->teachersRepository = $teachersRepository;
        $this->examsRepository = $examsRepository;
        $this->excersisesRepository = $excersisesRepository;
        $this->studentsRepository = $studentsRepository;
        $this->studentExamsRepository = $studentExamsRepository;
        $this->studentPicturesRepository = $studentPicturesRepository;
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/create", name="teacher_create_exam")
     */
    public function createExam(string $organisation, Request $request, UserInterface $user){
        $form = $this->createForm(createExamsFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $teacher = $this->teachersRepository->findOneBy(['User' => $user->getId()]);

            $organisationObject = $this->organisationsRepository->findOneBy(['token' => $organisation]);

            $exam = new Exams(
                $teacher,
                $organisationObject,
                $data['classPick'],
                $data['title'],
                $data['subject'],
                $data['date']
            );

            $this->entityManager->persist($exam);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się dodać sprawdzian');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }
        return $this->render('workspace/teacher/exams/createExam.html.twig', [
            'form' => $form->createView(),
            'organisation' => $organisation
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/list", name="teacher_list_exams")
     */
    public function listExam(string $organisation){
        $exams = $this->examsRepository->findAll();

        $exercises = $this->excersisesRepository->findBy([], ['grade' => 'DESC']);

        $actualDate = new \DateTime("now");

        foreach ($exams as $exam){
            $expireDate = $exam->getActiveDate();

            if($expireDate->getTimestamp() <= $actualDate->getTimestamp()){
                $exam->setIsActive(0);

                $this->entityManager->persist($exam);
                $this->entityManager->flush();

                $expiredExams[] = $exam;
            }else{
                $activeExams[] = $exam;
            }
        }
        return $this->render('workspace/teacher/exams/listExams.html.twig', [
            'organisation' => $organisation,
            'expired' => $expiredExams ?? null,
            'actual' => $activeExams ?? null,
            'exercises' => $exercises
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/edit/{examId}", name="teacher_edit_exam")
     */
    public function editExam(string $organisation, int $examId, Request $request){
        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        if(!$exam){
            $this->addFlash('error', 'Przykro nam, ale podany sprawdzian nie istnieje');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }else{
            $form = $this->createForm(editExamFormType::class, $exam);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $this->entityManager->persist($exam);
                $this->entityManager->flush();

                $this->addFlash('success', 'Sprawdzian został poprawnie edytowany');

                return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
            }

            return $this->render('workspace/teacher/exams/editExams.html.twig', [
                'form' => $form->createView(),
                'organisation' => $organisation
            ]);
        }
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/delete/{examId}", name="teacher_delete_exam")
     */
    public function deleteExam(string $organisation, int $examId){
        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        if(!$exam){
            $this->addFlash('error', 'Przykro nam, ale ten egzamin już nie istnieje');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }else{
            $this->entityManager->remove($exam);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się usunąć egzamin');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/activate/{examId}", name="teacher_activate_exam")
     */

    public function activateExam(string $organisation, int$examId){
        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        $exercises = $this->excersisesRepository->findOneBy(['exam' => $exam]);

        $date = new \DateTime("now");

        if(($exercises != null) && ($exam->getActiveDate()->getTimestamp() >= $date->getTimestamp())){
            $activate = $exam->setIsActive(1);

            $this->entityManager->persist($activate);
            $this->entityManager->flush();
        }else{
            $this->addFlash('error', 'Przykro nam, ale nie można aktywować tego sprawdzianu ze względu na to, że albo nie posiada on zadań albo data zakończenia sprawdzianu już jest przedawniona');
        }

        return $this->redirectToRoute('teacher_list_exams', [
            'organisation' => $organisation
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/report/{examId}", name="teacher_show_exam_report")
     */
    public function checkExam(string $organisation, int $examId){
        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        $date = new \DateTime("now");

        if(($exam->getActiveDate()->getTimestamp() <= $date->getTimestamp()) || ($exam->getIsActive() == 0)){
            $class = $exam->getClass();

            $classNumber = $class->getName();

            $students = $this->studentsRepository->findBy(['class' => $class]);

            foreach($students as $stu){
                $studentExam = $this->studentExamsRepository->findOneBy(['student' => $stu]);

                if(!$studentExam){
                    $user = $stu->getUser();

                    $notTaken[] = $user;
                }elseif ($studentExam->getIsDone() == 0){
                    $notDone[] = $studentExam;
                }else{
                    $done[] = $studentExam;

                    $studentPic = $this->studentPicturesRepository->findBy(['studentExams' => $studentExam]);

                    foreach ($studentPic as $pic){
                        $pics[] = $pic;
                    }
                }
            }

            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($pdfOptions);

            $html = $this->render('workspace/teacher/exams/examReportPdf.html.twig', [
                'organisation' => $organisation,
                'done' => $done ?? null,
                'pics' => $pics ?? null,
                'notDone' => $notDone ?? null,
                'notTaken' => $notTaken ?? null
            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($classNumber.'_report.pdf', [
                "Attachment" => true
            ]);

            return $this->render('workspace/teacher/exams/examReport.html.twig', [
                'organisation' => $organisation,
                'done' => $done ?? null,
                'pics' => $pics ?? null,
                'notDone' => $notDone ?? null,
                'notTaken' => $notTaken ?? null
            ]);
        }else{
            $this->addFlash('error', 'Przykro nam, ale żeby sprawdzić rozwiązania uczniów, sprawdzian musi być nie aktywny');

            return $this->redirectToRoute('teacher_list_exams', [
                'organisation' => $organisation
            ]);
        }
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/exercises/add/{examId}", name="teacher_add_exersises")
     */
    public function addExcercises(string $organisation, int $examId, Request $request){
        $form = $this->createForm(addExercisesFormType::class);
        $form->handleRequest($request);

        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $existingGroup = $this->excersisesRepository->findOneBy(['exam' => $examId, 'grade' => $data['grade'], 'exerciseGroup' => $data['group']]);

            if(!$existingGroup){
                $exercise = new Excersises(
                    $data['grade'],
                    $data['exercises'],
                    $data['group'],
                    $exam
                );

                $this->entityManager->persist($exercise);
                $this->entityManager->flush();

                $this->addFlash('success', 'Udało się dodać zadania do sprawdzianu');

                return $this->redirectToRoute('teacher_list_exams', [
                    'organisation' => $organisation
                ]);

            }else{
                $this->addFlash('error', 'Przykro nam, ale ta grupa ocenowa już istnieje');

                return $this->render('workspace/teacher/exams/exercises/addExercises.html.twig', [
                    'form' => $form->createView(),
                    'organisation' => $organisation
                ]);
            }
        }
        return $this->render('workspace/teacher/exams/exercises/addExercises.html.twig', [
            'form' => $form->createView(),
            'organisation' => $organisation
        ]);
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/exercises/edit/{exerciseId}/{examId}", name="teacher_edit_exercise")
     */
    public function editExercise(string $organisation, int $exerciseId, int $examId, Request $request){
        $exercise = $this->excersisesRepository->findOneBy(['id' => $exerciseId]);

        if(!$exercise){
            $this->addFlash('error', 'Podana grupa ocenowa nie istnieje');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }else{
            $form = $this->createForm(editExercisesFormType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();

                $existingGroup = $this->excersisesRepository->findBy(['exam' => $examId, 'grade' => $data['grade'], 'exerciseGroup' => $data['exerciseGroup']]);

                if(!$existingGroup){
                    if($data['grade'] != null){
                        $grade = $exercise->setGrade($data['grade']);

                        $this->entityManager->persist($grade);
                        $this->entityManager->flush();
                    }

                    if($data['exercises'] != null){
                        $ex = $exercise->setExcersises($data['exercises']);

                        $this->entityManager->persist($ex);
                        $this->entityManager->flush();
                    }

                    /*dd($data['grade'], $data['exerciseGroup']);*/

                    if($data['exerciseGroup'] != null){
                        $group = $exercise->setExerciseGroup($data['exerciseGroup']);

                        $this->entityManager->persist($group);
                        $this->entityManager->flush();
                    }

                    $this->addFlash('success', 'Grupa ocenowa została poprawnie edytowana');

                    return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
                }else{
                    $this->addFlash('error', 'Przykro nam, ale nie możesz tak edytować grupy ocenowej, ponieważ w tym sprawdzianie już istnieje grupa z podaną oceną lub grupą zadań');

                    return $this->render('workspace/teacher/exams/exercises/editExercises.html.twig', [
                        'form' => $form->createView(),
                        'organisation' => $organisation
                    ]);
                }
            }
            return $this->render('workspace/teacher/exams/exercises/editExercises.html.twig', [
                'form' => $form->createView(),
                'organisation' => $organisation
            ]);
        }
    }

    /**
     * @IsGranted("ROLE_TEACHER", subject="organisation")
     *
     * @Route("/exercises/delete/{exerciseId}", name="teacher_delete_exercise")
     */
    public function deleteExercise(string $organisation, int $exerciseId){
        $exercise = $this->excersisesRepository->findOneBy(['id' => $exerciseId]);

        if(!$exercise){
            $this->addFlash('error', 'Przykro nam, ale ta grupa ocenowa już nie istnieje');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }else{
            $this->entityManager->remove($exercise);
            $this->entityManager->flush();

            $this->addFlash('success', 'Udało się usunąć grupę ocenową');

            return $this->redirectToRoute('teacher_list_exams', ['organisation' => $organisation]);
        }
    }
}