<?php

namespace App\Controller;

use App\Form\PasswordFormType;
use App\Form\PrePasswordFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    private $entityManger;
    private $UsersRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UsersRepository $UsersRepository
    ){
        $this->entityManger = $entityManager;
        $this->UsersRepository = $UsersRepository;
    }

    /**
     * @Route("/email/change", name="send_email_change")
     */
    public function email(Request $request, MailerInterface $mailer){
        $form = $this->createForm(PrePasswordFormType::class);
        $form->handleRequest($request);

        $formData = $form->getData();

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->UsersRepository->findOneBy(['email' => $formData['email']]);

            if (is_null($user)) {
                $this->addFlash('error', 'Użytkownik z podanym adresem email nie istnieje');

                return $this->render('preChange.html.twig', [
                    'form' => $form->createView()
                ]);
            }else{
                $user->preChange();

                $this->entityManger->persist($user);
                $this->entityManger->flush();

                $url = $this->generateUrl('change_password', array('token' => $user->getToken()), UrlGenerator::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('bartlomiej.szyszkowski@yellows.eu')
                    ->to($user->getEmail())
                    ->subject('Link do zmiany hasła')
                    ->html($url);

                $mailer->send($email);

                $this->addFlash('success', 'Email z linikem do zmiany hasła został wysłany.');

                return $this->redirectToRoute('homepage');
            }
        }
        return $this->render('change.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/change/{token}", name="change_password")
     */
    public function change(string $token, Request $request)
    {
        $form = $this->createForm(PasswordFormType::class);
        $preForm = $this->createForm(PrePasswordFormType::class);
        $form->handleRequest($request);

        $formData = $form->getData();

        $user = $this->UsersRepository->findOneBy(['token' => $token]);

        $date = new \DateTime("now");

        if (is_null($user)) {
            throw new \Exception("User with token: {$token} not found", 404);
        }

        if ($user->getTokenExpire()->getTimestamp() > $date->getTimestamp()) {

            if ($form->isSubmitted() && $form->isValid()) {

                if($formData['1Password'] == $formData['2Password']) {

                    $user->change(
                        $formData['1Password']
                    );
                    $this->entityManger->persist($user);
                    $this->entityManger->flush();
                    $this->addFlash('success', 'Gratulacje, hasło zostało pomyślnie zmienione');

                    return $this->redirectToRoute('homepage');
                }else{
                    $this->addFlash('error', 'Podane hasła się nie zgadzają. Proszę poprawnie powtórzyć nowe hsało.');

                    return $this->render('change.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
            }
        }else{
            $this->addFlash('error', 'Link jest już nie aktywny, prosimy podać email w celu wsyłania nowego linka do zmiany hasła');

            return $this->render('preChange.html.twig', [
                'form' => $preForm->createView()
            ]);
        }
        return $this->render('change.html.twig', [
            'form' => $form->createView()
        ]);
    }
}