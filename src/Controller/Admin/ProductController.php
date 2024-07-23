<?php

namespace App\Controller\Admin;

use App\Entity\Parfums;
use App\Form\AdminProductType;
use App\Repository\ParfumsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin', name: 'admin_')]
class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ParfumsRepository $parfumsRepository): Response
    {
        $products=$parfumsRepository->findAll();
        //dd($products);

        return $this->render('admin/product/index.html.twig',['products' => $products]);
    }
//=============================USER_DELETE====================================
    #[Route('/product/new', name: 'product_new')]

    public function create(Request $request,  EntityManagerInterface $em, SecurityController $securityController,SluggerInterface $slugger):Response{

$products= new Parfums();
$form= $this->createForm(AdminProductType::class,$products);

$form->handleRequest($request);
$errors=[];
if ($form->isSubmitted() && $form->isValid()) {

    $isCoffret = $form->get('isCoffret')->getData();
    $products->setCoffret($isCoffret ?? false);  
    $imageFile = $form->get('image')->getData(); 
    if ($imageFile) {
        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
        try {
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
            $products->setImage($newFilename);
        } catch (FileException $e) {
            $this->addFlash('danger', 'Erreur lors du téléchargement de l\'image.');
            return $this->redirectToRoute('product_new');
        }
    } else {
        // Si aucun fichier n'est téléchargé, définissez l'image à null ou à une valeur par défaut
        $products->setImage('null');
    }
     
    $nom=$form->get('nom')->getData();
    
    $marque=$form->get('marque')->getData();
    $description=$form->get('description')->getData();
    $genre=$form->get('genre')->getData();
    $concentration=$form->get('concentration')->getData();

    $valideNom=$securityController->verifyText($nom);
    $valideMarque=$securityController->verifyText($marque);
    $valideDescription=$securityController->verifyText($description);
    $valideGenre=$securityController->verifyText($genre);
    $valideConcentration=$securityController->verifyText($concentration);
  

if ($valideNom == 0) {
  $errors['nom']  ='le nom n\'est pas valide';
}
if ($valideDescription == 0) {
  $errors['description']  ='la description n\'est pas valide';
}
if ($valideMarque == 0) {
  $errors['marque']  ='la marque n\'est pas valide';
}
if (  $valideConcentration == 0) {
  $errors['concentration']  ='la concentration n\'est pas valide';
}
if (  $valideGenre == 0) {
  $errors['concentration']  ='le genre n\'est pas valide';
}

if (empty($errors)) {
   $nom=$securityController->secu($nom);
   $marque=$securityController->secu($marque);
   $description=$securityController->secu($description);
   $genre=$securityController->secu($genre);
   $concentration=$securityController->secu($concentration);
   $products->setNom($nom);
   $products->setMarque($marque);
   $products->setDescription($description);
   $products->setGenre($genre);
   $products->setConcentration($concentration);
   $em->persist($products);
    $em->flush();
    return $this->redirectToRoute('admin_product', status:Response::HTTP_SEE_OTHER);
}
    
}

return $this->render('admin/product/newProduct.html.twig', [
    'form' => $form->createView(),
'errors'=>$errors]);
}

//=============================PRODUCT_DELETE====================================
#[Route('/product/delete', name: 'product_delete', methods:['POST'])]
public function delete(EntityManagerInterface $em, Request $request): Response{
    $productIds = $request->request->all('product_ids');
    //dd($productIds);
                 //$userIds = [$userIds]; 
                foreach ($productIds as $productId) {
                    if ($productId !== null) {
                        $product = $em->getRepository(Parfums::class)->find($productId);
                        $em->remove($product);
                    }
                    }  
                    $em->flush();  
                    return $this->redirectToRoute('admin_product');
                } 

//=============================product_edit====================================
private function CoffretStatus(FormInterface $form, Parfums $parfum)
{
    $isCoffret = $parfum->isCoffret();
    $form->get('isCoffret')->setData($isCoffret);}
    
#[Route('/product/edit/{id<\d+>}' , name:'product_edit', methods:['GET','POST'])]
public function edit(Request $request,  EntityManagerInterface $em, SecurityController $securityController,SluggerInterface $slugger, ParfumsRepository $parfumsRepository, Parfums $product):Response{

    $products=$parfumsRepository->find($product->getId());
    $form= $this->createForm(AdminProductType::class,$products);
    $this->CoffretStatus($form, $products);
    $form->handleRequest($request);
    $errors=[];
    if ($form->isSubmitted() && $form->isValid()) {
    
        $isCoffret = $form->get('isCoffret')->getData();
        if ($isCoffret) {
            $products->setCoffret(true);
        }
        else{
            $products->setCoffret(false);
        }
        $imageFile = $form->get('image')->getData(); 
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
        
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            } catch (FileException $e) {
                $this->addFlash('danger', 'Erreur lors du téléchargement de l\'image.');
                return $this->redirectToRoute('product_new');
            }
        } else {
            $products->setImage('null');
        }
          
        $nom=$form->get('nom')->getData();
    $marque=$form->get('marque')->getData();
    $description=$form->get('description')->getData();
    $genre=$form->get('genre')->getData();
    $concentration=$form->get('concentration')->getData();

    $valideNom=$securityController->verifyText($nom);
    $valideMarque=$securityController->verifyText($marque);
    $valideDescription=$securityController->verifyText($description);
    $valideGenre=$securityController->verifyText($genre);
    $valideConcentration=$securityController->verifyText($concentration);
  

if ($valideNom == 0) {
  $errors['nom']  ='le nom n\'est pas valide';
}
if ($valideDescription == 0) {
  $errors['description']  ='la description n\'est pas valide';
}
if ($valideMarque == 0) {
  $errors['marque']  ='la marque n\'est pas valide';
}
if (  $valideConcentration == 0) {
  $errors['concentration']  ='la concentration n\'est pas valide';
}
if (  $valideGenre == 0) {
  $errors['concentration']  ='le genre n\'est pas valide';
}

if (empty($errors)) {
   $nom=$securityController->secu($nom);
   $marque=$securityController->secu($marque);
   $description=$securityController->secu($description);
   $genre=$securityController->secu($genre);
   $concentration=$securityController->secu($concentration);
   $products->setNom($nom);
   $products->setMarque($marque);
   $products->setDescription($description);
   $products->setGenre($genre);
   $products->setConcentration($concentration);
   $em->persist($products);
    $em->flush();
    return $this->redirectToRoute('admin_product', status:Response::HTTP_SEE_OTHER);
}
    }
    
    return $this->render('admin/product/editProduct.html.twig', [
        'form' => $form->createView(),'product' => $products, 'errors'=>  $errors
    ]);
    }

}

