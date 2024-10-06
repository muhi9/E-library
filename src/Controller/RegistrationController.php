<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, VerifyEmailHelperInterface $verifyEmailHelper ,MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher,
     UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $signatureComponents = $verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail(),
                ['id' => $user->getId()]
            );
         
            $email = (new Email())
            ->from('muhi-tu@abv.bg')
            ->to($user->getEmail())
            ->subject('Please confirm your email')
            ->text("Confirm your email at: ".$signatureComponents->getSignedUrl());
        
            $mailer->send($email);
            $this->addFlash('success', 'Please check your email for confirm your account!');
          
            return $this->redirectToRoute('login');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request,VerifyEmailHelperInterface $verifyEmailHelper, 
    TranslatorInterface $translator, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($request->query->get('id'));
        if (!$user) {
            throw $this->createNotFoundException();
        }
        try {
            $verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                $user->getId(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
            return $this->redirectToRoute('register');
        }
        
        $user->setIsVerified(true);
        $entityManager->flush();
        $this->addFlash('success', 'Account Verified! You can now log in.');
        return $this->redirectToRoute('index');
    }
    
}
