<?php

namespace App\Controller\workspace\student;

use App\Entity\StudentExams;
use App\Entity\StudentPictures;
use App\Form\workspace\student\studentChooseFormType;
use App\Form\workspace\student\studentSolvingFormType;
use App\Repository\ExamsRepository;
use App\Repository\ExcersisesRepository;
use App\Repository\StudentExamsRepository;
use App\Repository\StudentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/student/workspace/{organisation}")
 */

class studentExamsController extends AbstractController
{
    private $examsRepository;
    private $studentsRepository;
    private $studentExamsRepository;
    private $exercisesRepository;
    private $entityManager;

    public function __construct(
        ExamsRepository $examsRepository,
        StudentsRepository $studentsRepository,
        StudentExamsRepository $studentExamsRepository,
        ExcersisesRepository $exercisesRepository,
        EntityManagerInterface $entityManager
    ){
        $this->examsRepository = $examsRepository;
        $this->studentsRepository = $studentsRepository;
        $this->studentExamsRepository = $studentExamsRepository;
        $this->exercisesRepository = $exercisesRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/exams/list/all", name="student_list_all_exams")
     */
    public function listAll(string $organisation, string $classToken, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $class = $student->getClass()->getId();

        $exams = $this->examsRepository->findBy(['class' => $class]);

        return $this->render('workspace/student/studentListAll.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken,
            'exams' => $exams ?? null
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/exams/list/actual", name="student_list_active_exams")
     */
    public function listActual(string $organisation, string $classToken, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $class = $student->getClass()->getId();

        $exams = $this->examsRepository->findBy(['class' => $class]);

        $date = new \DateTime("now");

        foreach ($exams as $exam){
            if($exam->getActiveDate()->getTimestamp() >= $date->getTimestamp()){
                $activeExams[] = $exam;
            }
        }

        return $this->render('workspace/student/studentListActive.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken,
            'activeExams' => $activeExams ?? null
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/{examId}/start/solving", name="student_start_solving_exam")
     */
    public function startSolving(string $organisation, string $classToken, int $examId, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $existingStudentExam = $this->studentExamsRepository->findOneBy(['student' => $student, 'exam' => $examId]);

        $exam = $this->examsRepository->findOneBy(['id' => $examId]);

        if(!$existingStudentExam){
            $newStudentExam = new StudentExams(
                $student,
                $exam
            );

            $this->entityManager->persist($newStudentExam);
            $this->entityManager->flush();

            $sExamId = $newStudentExam->getId();

            $this->addFlash('success', 'Gratulacje uczniu, właśnie rozpocząłeś rozwiązywać sprawdzian');

            return $this->redirectToRoute('student_keep_solving_exam', ['organisation' => $organisation, 'classToken' => $classToken, 'examId' => $examId, 'studentExamId' => $sExamId]);
        }else{
            $sExamId = $existingStudentExam->getId();

            $this->addFlash('info', 'Właśnie kontynuujesz rozwiązywanie sprawdzianu');

            return $this->redirectToRoute('student_keep_solving_exam', ['organisation' => $organisation, 'classToken' => $classToken, 'examId' => $examId, 'studentExamId' => $sExamId]);
        }
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/{examId}/keep/sloving/{studentExamId}", name="student_keep_solving_exam")
     */
    public function keepSolving(string $organisation, string $classToken, int $examId, int $studentExamId, Request $request){
        $form = $this->createForm(studentChooseFormType::class);
        $form->handleRequest($request);

        $studentExam = $this->studentExamsRepository->findOneBy(['id' => $studentExamId]);

        $studentExamExercises = $studentExam->getExercises();

        if($studentExamExercises != null){
            $this->addFlash('success', 'Już masz przydzielone zadania do tego sprawdzianu zacznij je teraz rozwiązywać');

            return $this->redirectToRoute('student_form_solving_exam', ['organisation' => $organisation, 'classToken' => $classToken, 'examId' => $examId, 'studentExamId' => $studentExamId]);
        }else{
            if($form->isSubmitted() && $form->isValid()){
                $data = $form->getData();

                switch($data['grade']){
                    case 2:
                        $group = rand(1, 2);
                        $exerciseGroup = $this->exercisesRepository->findOneBy(['exam' => $examId, 'grade' => 2, 'exerciseGroup' => $group]);
                        break;
                    case 3:
                        $group = rand(1, 2);
                        $exerciseGroup = $this->exercisesRepository->findOneBy(['exam' => $examId, 'grade' => 3, 'exerciseGroup' => $group]);
                        break;
                    case 4:
                        $group = rand(1, 2);
                        $exerciseGroup = $this->exercisesRepository->findOneBy(['exam' => $examId, 'grade' => 4, 'exerciseGroup' => $group]);
                        break;
                    case 5:
                        $group = rand(1, 2);
                        $exerciseGroup = $this->exercisesRepository->findOneBy(['exam' => $examId, 'grade' => 5, 'exerciseGroup' => $group]);
                        break;
                    case 6:
                        $group = rand(1, 2);
                        $exerciseGroup = $this->exercisesRepository->findOneBy(['exam' => $examId, 'grade' => 6, 'exerciseGroup' => $group]);
                        break;
                }

                if($exerciseGroup != null){
                    $updateStudentExam = $studentExam->setExercises($exerciseGroup);

                    $this->entityManager->persist($updateStudentExam);
                    $this->entityManager->flush();

                    $this->addFlash('success', 'Udało się przydzielić grupę ocenową');

                    return $this->redirectToRoute('student_list_active_exams', [
                        'organisation' => $organisation,
                        'classToken' => $classToken
                    ]);
                }else{
                    $this->addFlash('error', 'Niestety nauczyciel nie uwzględnił takiej grupy ocenowej, spróbuj podać inną ocenę.');

                    return $this->render('workspace/student/studentChoose.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }
        }

        return $this->render('workspace/student/studentChoose.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/{examId}/form/solving/{studentExamId}", name="student_form_solving_exam", methods={"GET", "POST"})
     */
    public function formSolving(Request $request, string $organisation, string $classToken, int $examId, int $studentExamId, SluggerInterface $slugger){
        $form = $this->createForm(studentSolvingFormType::class);
        $form->handleRequest($request);

        $studentExam = $this->studentExamsRepository->findOneBy(['id' => $studentExamId]);

        $exercises = $studentExam->getExercises();

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            /** @var UploadedFile $pic */
            $picArray = $data['picture'];

            foreach($picArray as $pic){
                $originalName = pathinfo($pic->getClientOriginalName(), PATHINFO_FILENAME);

                $safeName = $slugger->slug($originalName);

                $newName = $safeName.'-'.uniqid().'.'.$pic->guessExtension();

                try{
                    $pic->move(
                        $this->getParameter('uploads'),
                        $newName
                    );
                } catch(FileException $e){
                    $this->addFlash('error', $e);
                }

                $picture = new StudentPictures(
                    $newName,
                    $studentExam
                );

                $this->entityManager->persist($picture);
                $this->entityManager->flush();
            }

            $studentIsDone = $studentExam->setIsDone(1);

            $this->entityManager->persist($studentIsDone);
            $this->entityManager->flush();

            $this->addFlash('success', 'Gratulacje, właśnie oddałeś swoje rozwiązania do sprawdzianu');

            return $this->redirectToRoute('student_list_solved_exams', ['organisation' => $organisation, 'classToken' => $classToken]);
        }
        return $this->render('workspace/student/studentFormSolving.html.twig', [
            'form' => $form->createView(),
            'organisation' => $organisation,
            'classToken' => $classToken,
            'exercises' => $exercises
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/exams/list/solved", name="student_list_solved_exams")
     */
    public function listSolved(string $organisation, string $classToken, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $class = $student->getClass()->getId();

        $exams = $this->examsRepository->findBy(['class' => $class]);

        foreach($exams as $exam){
            $studentExam = $this->studentExamsRepository->findOneBy(['student' => $student, 'exam' => $exam]);

            if($studentExam){
                $isDone = $studentExam->getIsDone();

                if($isDone == 1){
                    $solvedExams[] = $exam;
                }
            }
        }

        return $this->render('workspace/student/studentListSolved.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken,
            'solvedExams' => $solvedExams ?? null
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/exams/list/unsolved", name="student_list_unsolved_exams")
     */
    public function listUnsolved(string $organisation, string $classToken, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $class = $student->getClass()->getId();

        $exams = $this->examsRepository->findBy(['class' => $class]);

        foreach($exams as $exam){
            $studentExam = $this->studentExamsRepository->findOneBy(['student' => $student, 'exam' => $exam]);

            if(!$studentExam){
                $unsolvedExams[] = $exam;
            }else{
                $isDone = $studentExam->getIsDone();

                if($isDone == 0){
                    $unsolvedExams[] = $exam;
                }
            }
        }

        return $this->render('workspace/student/studentListUnsolved.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken,
            'unsolvedExams' => $unsolvedExams ?? null
        ]);
    }

    /**
     * @IsGranted("ROLE_STUDENT", subject="organisation")
     *
     * @Route("/{classToken}/exams/list/expired", name="student_list_expired_exams")
     */
    public function listExpired(string $organisation, string $classToken, UserInterface $user){
        $id = $user->getId();

        $student = $this->studentsRepository->findOneBy(['user' => $id]);

        $class = $student->getClass()->getId();

        $exams = $this->examsRepository->findBy(['class' => $class]);

        $date = new \DateTime("now");

        foreach ($exams as $exam){
            if($exam->getActiveDate()->getTimestamp() <= $date->getTimestamp()){
                $expiredExams[] = $exam;
            }
        }

        return $this->render('workspace/student/studentListExpired.html.twig', [
            'organisation' => $organisation,
            'classToken' => $classToken,
            'expiredExams' => $expiredExams ?? null
        ]);
    }
}