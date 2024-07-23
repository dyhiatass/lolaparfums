<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\DetailsParfum;
use App\Form\CoffretFilterType;
use App\Entity\PanierItemParfum;
use App\Entity\Parfums;
use App\Form\ParfumFemFilterType;
use App\Form\ParfumHomFilterType;
use App\Repository\ParfumsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailsParfumRepository;
use App\Repository\PanierItemParfumRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    #[Route('/parfum-femme', name: 'parfum_femme')]
    public function parfumFemme(ParfumsRepository $parfumsRepository, Request $request, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        // Créez le formulaire de filtre
        $form = $this->createForm(ParfumFemFilterType::class);
        $form->handleRequest($request);

        $filters = $form->getData();

        $parfums = $parfumsRepository->findByParfums(
            $filters['marque'] ?? null,
            $filters['concentration'] ?? null,
            $filters['prix'] ?? null,
            'femme'

        );
        //dd($parfums);
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('parfumFemme/index.html.twig', [
            'parfums' => $parfums,
            'totalItemsPanier' => $totalItemsPanier,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/parfum-homme', name: 'parfum_homme')]
    public function parfumHomme(ParfumsRepository $parfumsRepository, Request $request, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        // Créez le formulaire de filtre
        $form = $this->createForm(ParfumHomFilterType::class);
        $form->handleRequest($request);

        $filters = $form->getData();

        $parfums = $parfumsRepository->findByParfums(
            $filters['marque'] ?? null,
            $filters['concentration'] ?? null,
            $filters['prix'] ?? null,
            'homme'

        );
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('parfumHomme/index.html.twig', [
            'parfums' => $parfums,
            'totalItemsPanier' => $totalItemsPanier,
            'form' => $form->createView(),
        ]);
    }

 // Créez le formulaire de filtre
   // traiter les données soumises par le formulaire.
    // Les données du formulaire sont ensuite récupérées
    
    #[Route('/coffret', name: 'coffret')]
        public function coffret(ParfumsRepository $parfumsRepository, Request $request, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        $form = $this->createForm(CoffretFilterType::class);
        $form->handleRequest($request);
        $filters = $form->getData();
        $coffrets = $parfumsRepository->findByCoffrets(
            $filters['marque'] ?? null,
            $filters['prix'] ?? null,
        );
        $coffretsTendences = $parfumsRepository->findBy(['isCoffret' => true], ['tendance' => 'ASC'], null, 5);
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('coffret/index.html.twig', [
            'coffrets' => $coffrets,
            'coffretsTendences' => $coffretsTendences,
            'totalItemsPanier' => $totalItemsPanier,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/bestsellers', name: 'bestsellers')]
    public function bestsellers(ParfumsRepository $parfumsRepository, Request $request, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        // Créez le formulaire de filtre
        $form = $this->createForm(CoffretFilterType::class);
        $form->handleRequest($request);

        $filters = $form->getData();

        $bestsellers = $parfumsRepository->findByBestsellers(
            $filters['marque'] ?? null,
            $filters['prix'] ?? null

        );
        $topBestsellers = $parfumsRepository->findBy(
            ['meilleursVente' => true, 'tendance' => true],
            ['id' => 'DESC'],
            5
        );
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('bestsellers/index.html.twig', [
            'bestsellers' => $bestsellers,
            'topBestsellers' => $topBestsellers,
            'totalItemsPanier' => $totalItemsPanier,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/promotions', name: 'promotions')]
    public function promotions(DetailsParfumRepository $detailsParfumRepository, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        // Récupère toutes les promotions disponibles
        $promotions = $detailsParfumRepository->findBy(['promotion' => true]);

        // Tableau pour stocker les promotions des coffrets
        $promotionCoffret = [];

        // Tableau pour stocker les promotions des parfums par ID, en sélectionnant la plus petite taille
        $promotionParfums = [];

        // Parcourt toutes les promotions
        foreach ($promotions as $promotion) {
            // Vérifie si l'entité associée est un coffret ou un parfum
            $parfum = $promotion->getParfums();
            if ($parfum->isCoffret()) {
                // Ajoute à la liste des coffrets
                $promotionCoffret[] = $promotion;
            } else {
                // Récupère l'ID du parfum
                $parfumId = $parfum->getId();

                // Convertit la taille en entier en supprimant le suffixe 'ml'
                $taille = (int) rtrim($promotion->getTaille(), 'ml');

                // Si aucune promotion n'est encore enregistrée pour ce parfum
                // ou si la taille de la promotion actuelle est plus petite que celle déjà enregistrée,
                // enregistre la promotion actuelle
                if (!isset($promotionParfums[$parfumId]) || $taille < (int) rtrim($promotionParfums[$parfumId]->getTaille(), 'ml')) {
                    $promotionParfums[$parfumId] = $promotion;
                }
            }
        }
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        // Rend la vue avec les données de promotions des coffrets et des parfums
        return $this->render('promotion/index.html.twig', [
            'promotionCoffrets' => $promotionCoffret,
            'totalItemsPanier' => $totalItemsPanier,
            'promotionParfums' => $promotionParfums,
        ]);
    }
    #[Route('/produits-promotions/{id}', name: 'produits_promotions')]
    public function produitsPromotions(PanierItemParfumRepository $panierItemParfumRepository, DetailsParfumRepository $detailsParfumRepository, int $id): Response
    {
        // Recherche l'entité DetailsParfum par ID
        $promotion = $detailsParfumRepository->find($id);

        if (!$promotion) {
            return $this->redirectToRoute('produit_detail', ['id' => $id]);
        }

        // Recherche l'entité Parfums associée
        $produit = $promotion->getParfums();

        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        //dd($promotion);
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('produit-promotion/detail_promotion.html.twig', [
            'produit' => $produit,
            'totalItemsPanier' => $totalItemsPanier,
            'promotion' => $promotion,
        ]);
    }

    #[Route('/produit/{id<\d+>}', name: 'produit_detail')]
    public function detail(PanierItemParfumRepository $panierItemParfumRepository, ParfumsRepository $parfumsRepository, Parfums $parfums): Response
    {
        $produit = $parfumsRepository->find($parfums->getId());
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        } //dd($produit);
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'totalItemsPanier' => $totalItemsPanier,
        ]);
    }

    #[Route('/ajouter-au-panier/{id<\d+>}', name: 'ajouter_au_panier', methods: ['GET', 'POST'])]
    public function ajouterAuPanier(Request $request, EntityManagerInterface $entityManager, ParfumsRepository $parfumsRepository, int $id, DetailsParfumRepository $detailsParfumRepository): Response
    { // Récupération de l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('security_login');
        }

        // Récupération du produit (parfum) par son ID
        $produit = $parfumsRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }   // Récupération de la taille sélectionnée (sauf si c'est un coffret)
        $taille = $request->request->get('taille');
        if (!$produit->isCoffret() && !$taille) {
            $this->addFlash('error', 'Veuillez sélectionner une taille');
            return $this->redirectToRoute('produit_detail', ['id' => $produit->getId()]);
        }

        // Récupération ou création du panier de l'utilisateur
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]) ?? new Panier();
        if (!$panier->getId()) {
            $panier->setUser($user);
            $panier->setDateCreation(new \DateTimeImmutable());
            $entityManager->persist($panier);
        }

        // Récupération du détail du parfum correspondant à la taille sélectionnée ou au coffret
        if ($produit->isCoffret()) {
            $detailsParfum = $detailsParfumRepository->findOneBy(['parfums' => $produit]);
        } else {
            $detailsParfum = $detailsParfumRepository->findOneBy(['parfums' => $produit, 'taille' => $taille]);
        }

        if (!$detailsParfum) {
            throw $this->createNotFoundException('Aucun détail de parfum trouvé pour ce produit');
        }
        //dd($detailsParfum);
        // Création d'un nouvel élément du panier pour ce produit (parfum)
        
        $quantite = 1;
        $panierItemParfum = new PanierItemParfum();
        $panierItemParfum->setDetailsParfum($detailsParfum);
        $panierItemParfum->setQuantite($quantite);
        $panierItemParfum->setPrixTotal($detailsParfum->getPrix() * $quantite);
        $panierItemParfum->setPanier($panier);

        if (!$produit->isCoffret()) {
            $panierItemParfum->setTaille($taille);
        } else {
            $panierItemParfum->setTaille(null);
        }
        // Ajout de l'élément au panier
        $panier->addPanierItemParfum($panierItemParfum);
        // Persistance de l'élément du panier et du panier dans la base de données
        $entityManager->persist($panier);
        $entityManager->persist($panierItemParfum);
        $entityManager->flush();

        // Redirection vers la page du panier
        return $this->redirectToRoute('home');
    }


    #[Route('/panier', name: 'panier_afficher')]
    public function afficherPanier(EntityManagerInterface $entityManager, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('security_login');}
        $panier = $entityManager->getRepository(Panier::class)->findOneBy(['user' => $user]);
        if (!$panier) {
            return $this->render('panier/afficher.html.twig', [
                'produits' => [],    ]);
        }
        $produits = $panierItemParfumRepository->findAll();
        //dd($produits);
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('panier/afficher.html.twig', [
            'produits' => $produits,
            'totalItemsPanier' => $totalItemsPanier,    ]); }


    #[Route('/panier-ajout/{id}', name: 'panier_ajouter')]
    public function AjoutQuantity(PanierItemParfum $item, EntityManagerInterface $entityManager): Response
    {
        // Utilisez l'entité DetailsParfum pour obtenir la quantité maximale
        $maxQuantity = $item->getDetailsParfum()->getQuantite();
        if ($item->getQuantite() < $maxQuantity) {
            $item->setQuantite($item->getQuantite() + 1);
            $entityManager->persist($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_afficher');
    }

    #[Route('/panier-suppression/{id}', name: 'panier_supprimer')]
    public function SupressionQuantity(PanierItemParfum $item, EntityManagerInterface $entityManager): Response
    {
        if ($item->getQuantite() > 1) {
            $item->setQuantite($item->getQuantite() - 1);
            $entityManager->persist($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_afficher');
    }

    #[Route('/panier/delete', name: 'panier_delete', methods: ['POST'])]
    public function deletePanierItemParfum(Request $request, EntityManagerInterface $em): Response
    {
        $ItemIds = $request->request->all('item_ids'); 
        foreach ($ItemIds as $ItemId) {
            if ($ItemId !== null) {
                $Item = $em->getRepository(PanierItemParfum::class)->find($ItemId);
                $em->remove($Item);
            }
        }
        $em->flush();
        return $this->redirectToRoute('panier_afficher');
    }
}
