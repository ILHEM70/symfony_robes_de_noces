<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
public function __construct(private EmailVerifier $emailVerifier) {}

#[Route('/register', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
$user = new User();
$form = $this->createForm(RegistrationFormType::class, $user);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
// Hash the plain password
$user->setPassword(
$userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData())
);

$entityManager->persist($user);
$entityManager->flush();

// Send email verification link
$this->emailVerifier->sendEmailConfirmation(
'app_verify_email',
$user,
(new TemplatedEmail())
->from(new Address('robes_de_noces@confirmation.com', 'Robes De Noces Bot'))
->to((string) $user->getEmail())
->subject('Please Confirm your Email')
->htmlTemplate('registration/confirmation_email.html.twig')
);

$this->addFlash('success', 'Registration successful! Please check your email.');

return $this->redirectToRoute('app_home');
}

return $this->render('registration/register.html.twig', [
'registrationForm' => $form->createView(),
'bodyClass' => 'inscription-image'
]);
}

#[Route('/verify/email', name: 'app_verify_email')]
public function verifyUserEmail(Request $request): Response
{
try {
$this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
} catch (VerifyEmailExceptionInterface $exception) {
$this->addFlash('verify_email_error', $exception->getReason());

return $this->redirectToRoute('app_register');
}

$this->addFlash('success', 'Your email address has been verified.');

return $this->redirectToRoute('app_home');
}
}