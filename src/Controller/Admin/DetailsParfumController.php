<?php

namespace App\Controller\Admin;

use App\Entity\DetailsParfum;
use App\Form\AdminDetailsParfumsType;
use App\Repository\DetailsParfumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\SecurityController;

#[Route('admin', name: 'admin_')]
class DetailsParfumController
extends AbstractController
{
    #[Route('/details', name: 'details')]
    public function index(DetailsParfumRepository $DetailsParfumRepository): Response
    {
        $DetailsParfumDetails = $DetailsParfumRepository->findAll();

        return $this->render('admin/DetailsParfum/index.html.twig', [
            'parfumsDetails' => $DetailsParfumDetails,
        ]);
    }
    //=============================PRODUCT_DELETE====================================
  

#[Route('/details/new', name: 'details_new')]
public function create(Request $request, EntityManagerInterface $em): Response
{
    $detailsParfum = new DetailsParfum();
    $form = $this->createForm(AdminDetailsParfumsType::class, $detailsParfum);
    $form->handleRequest($request);
   

    if ($form->isSubmitted() && $form->isValid()) {
        
            
            $em->persist($detailsParfum);
            $em->flush();

            return $this->redirectToRoute('admin_details', [], Response::HTTP_SEE_OTHER);
        
    }

    return $this->render('admin/detailsParfum/newDetailsParfum.html.twig', [
        'form' => $form->createView(),
        
    ]);
}


    //=============================PRODUCT_DELETE====================================

    #[Route('/details/edit/{id<\d+>}', name: 'details_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, DetailsParfumRepository $DetailsParfumRepository, DetailsParfum $DetailsParfum, SecurityController $securityController): Response
    {
        $DetailsParfum = $DetailsParfumRepository->find($DetailsParfum->getId());;
        $form = $this->createForm(AdminDetailsParfumsType::class, $DetailsParfum);
        $form->handleRequest($request);
$errors=[];
        if ($form->isSubmitted() && $form->isValid()) {
            $prix = $form->get('prix')->getData();
            $quantite = $form->get('quantite')->getData();
            $pourcentage = $form->get('pourcentagePromotion')->getData();

            $validePrix = $securityController->verifyPrix($prix);
            $valideQuantite = $securityController->verifyQuantity($quantite);
            $validePourcentage = $securityController->verifyPourcentage($pourcentage);
            
            if ($validePrix == 0) {
                $errors['prix'] = 'le prix n\'est pas valide';
            }
            if ($valideQuantite == 0) {
                $errors['quantite'] = 'la quantité n\'est pas valide';
            }
            if ($validePourcentage == 0) {
                $errors['pourcentagePromotion'] = 'le pourcentage n\'est pas valide';
            }

            if (empty($errors)) {
                $prix = $securityController->secu($prix);
                $quantite = $securityController->secu($quantite);
                $pourcentage = $securityController->secu($pourcentage);
                $DetailsParfum->setPrix($prix);
                $DetailsParfum->setQuantite($quantite);
                $DetailsParfum->setPourcentagePromotion($pourcentage);
                $em->persist($DetailsParfum);
                $em->flush();

                return $this->redirectToRoute('admin_details', status: Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/detailsParfum/editDetailsParfum.html.twig', [
            'form' => $form->createView(),
            'errors'=>$errors]);
    }
    //=============================PRODUCT_DELETE====================================
    #[Route('/details/delete', name: 'details_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request): Response
    {
        $ProductDetailsIds = $request->request->all('parfumsDetail_ids');
        foreach ($ProductDetailsIds as $ProductDetailsId) {
            if ($ProductDetailsId !== null) {
                $product = $em->getRepository(DetailsParfum::class)->find($ProductDetailsId);
                $em->remove($product);
            }
        }
        $em->flush();
        return $this->redirectToRoute('admin_details');
    }
}
