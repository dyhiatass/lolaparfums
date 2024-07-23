<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Panier;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormInterface;


#[Route('/admin', name: 'admin_')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(UserRepository $userRepository): Response
    {
        $users=$userRepository->findAll();

        return $this->render('admin/user/index.html.twig', ['users' => $users]);
    }
//=============================USER_NEW=========================================
#[Route('/user/new', name: 'user_new', methods:['GET', 'POST'])]
        public function create(Request $request,EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder, SecurityController $securityController ):Response{
            $user= new User();
            $form = $this->createForm(AdminUserType::class, $user);
            $form->handleRequest($request);
            $errors=[];
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('roles')) {
                    $user->setRoles(['ROLE_ADMIN']);}
                    else{
                        $user->setRoles([]);
                    }
                    
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
                        
                        return $this->redirectToRoute('admin_user', status: Response::HTTP_SEE_OTHER);  
                    }              
                }
                return $this->render('admin/user/newUser.html.twig', ['form'=> $form->createView(),'errors' => $errors ]);    
            }
            //=============================USER_DELETE====================================
            
            #[Route('/user/delete', name: 'user_delete' , methods:['GET','POST'])]
            public function delete(EntityManagerInterface $em, Request $request): Response
           {$userIds = $request->request->all('user_ids'); 
                //$userIds = [$userIds]; 
               foreach ($userIds as $userId) {
                   if ($userId !== null) {
                       $user = $em->getRepository(User::class)->find($userId);
                       $em->remove($user);
                   }
                   }  
                   $em->flush();  
                   return $this->redirectToRoute('admin_user');
               }
                ////=============================USER_EDIT=======================
                private function AdminRole(FormInterface $form, User $user){
                   $userRole= $user->getRoles();
                   $isAdmin= in_array('ROLE_ADMIN',$userRole );
                   $form->get('roles')->setData($isAdmin);   
               }
               #[Route('/user/edit/{id<\d+>}', name: 'user_edit' , methods:['POST','GET'])]
               public function edit(UserRepository $userRepository, EntityManagerInterface $em, Request $request, User $user, SecurityController $securityController,UserPasswordHasherInterface $passwordEncoder):Response{
                   $user=$userRepository->find($user->getId());
                   $errors=[];
                   $form = $this->createForm(AdminUserType::class, $user);
                   $this->AdminRole($form, $user);
                   $form->handleRequest($request);
                   if($form->isSubmitted() && $form->isValid()){
                        
                       $RoleChecked = $form->get('roles')->getData();
                       //dd($RoleChecked);
                       if ($RoleChecked) {
                           $user->setRoles(['ROLE_ADMIN']);
                       } else {
                           
                           $user->setRoles([]);
                       }
                       $password =$form->get('password')->getData();
                       $prenom =$form->get('prenom')->getData();
                       $nom =$form->get('nom')->getData();
                       $mail =$form->get('email')->getData();
                       $validePrenom= $securityController->verifyText($prenom);
                       $valideNom= $securityController->verifyText($nom);
                       $valideMail= $securityController->verifyMail($mail);
                       $validePassword= $securityController->verifyMdp($password);
                       
                       
                       if ($validePrenom !== 1) {
                           $errors['prenom'] = 'Le prénom n\'est pas valide';
                       }
                       if ($valideNom !== 1) {
                           $errors['nom'] = 'Le nom n\'est pas valide';
                       }
                       if ($valideMail !== 1) {
                           $errors['mail'] = 'L\'adresse e-mail n\'est pas valide';
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
                           
                           return $this->redirectToRoute('admin_user', status: Response::HTTP_SEE_OTHER);  
                       }           }
                       
                       return $this->render('admin/user/editUser.html.twig', ['form'=> $form->createView(),'errors' => $errors ]);
                       
                   }
                   
                }