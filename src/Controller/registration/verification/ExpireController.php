<?php
namespace App\Controller\registration\verification;

use App\Form\ExpireFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;

class ExpireController extends AbstractController
{
    private $entityManager;
    private $UserRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $UserRepository
    ){
        $this->entityManager = $entityManager;
        $this->UserRepository = $UserRepository;
    }
    /**
     * @Route("/expire/{token}", name="expire")
     */
    public function expire(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ExpireFormType::class);
        $form->handleRequest($request);

        $formData = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->UserRepository->findOneBy(['email' => $formData['email']]);
            if(!$user){
                $this->addFlash('error', 'Przykro nam, ale użytkownik z podanym adresem email nie istnieje');
            }else{
                $user->expire();

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $url = $this->generateUrl('app_activate_active', array('token' => $user->getToken()), UrlGenerator::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('bartlomiej.szyszkowski@yellows.eu')
                    ->to($user->getEmail())
                    ->subject('Activate your account')
                    ->html('Link aktywacyjny: '.$url);

                $mailer->send($email);

                $this->addFlash('success', 'Nowy link aktywacyjny został wysłany nma podany adres mailowy');

                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('registration/verification/expire.html.twig', [
            'form' => $form->createView()
        ]);
    }
}