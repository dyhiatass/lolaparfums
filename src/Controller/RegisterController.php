<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ContactType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\SecurityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
  #[Route('/inscription', name: 'inscription')]
  public function Registration(Request $request,EntityManagerInterface $em, SecurityController $securityController, UserPasswordHasherInterface $passwordEncoder ): Response
  {
    $user = new User(); 
    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);
    $errors=[];
    if ($form->isSubmitted() && $form->isValid()) {
      $user->setRoles([]);
      $password =$form->get('password')->getData();
      $prenom =$form->get('prenom')->getData();
      $nom =$form->get('nom')->getData();
      $mail =$form->get('email')->getData();
      $validePrenom= $securityController->verifyText($prenom);
      $valideNom= $securityController->verifyText($nom);
      $valideMail= $securityController->verifyMail($mail);
      $validePassword= $securityController->verifyMdp($password);
      
      if ($validePrenom == 0) {
        $errors['prenom'] = 'Le prénom n\'est pas valide';
      }
      if ($valideNom !== 1) {
        $errors['nom'] = 'Le nom n\'est pas valide';
      }
      if ($valideMail !== 1) {
        $errors['email'] = 'L\'adresse e-mail n\'est pas valide';
      }
      if ($validePassword !== 1) {
        $errors['password'] = 'Le mot de passe n\'est pas valide';
      }
      
      if (empty($errors)) {
        
        $password = $securityController->secu($password);
        $prenom = $securityController->secu($prenom);
        $nom = $securityController->secu($nom);
        $hashedPassword = $passwordEncoder->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setPrenom($prenom);
        $user->setNom($nom);
        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('security_login', status: Response::HTTP_SEE_OTHER);  
      }              
    }
    return $this->render('register/registration.html.twig', ['form'=> $form->createView(),'errors' => $errors ]);    
  }

  #[Route('/connexion', name: 'security_login', methods: ['GET', 'POST'])]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
      
      $error = $authenticationUtils->getLastAuthenticationError();
      
      $errorMessage = null;
      if ($error) {
          $errorMessage = 'Identifiants incorrects. Veuillez réessayer.';
      }

      return $this->render('security/login.html.twig', [
          
          'error' => $errorMessage,
      ]);
  }
  #[Route('/déconnexion', name: 'security_logout')]
  public function logout(){

return $this->render('security/login.html.twig');

}


}