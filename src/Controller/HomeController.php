<?php

namespace App\Controller;

use App\Repository\PanierItemParfumRepository;
use App\Repository\ParfumsRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ParfumsRepository $parfumsRepository, PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        $coffrets = $parfumsRepository->findBy(['isCoffret' => true], null, 5);
        $meilleursVente = $parfumsRepository->findBy(['meilleursVente' => true], null, 5);
        $tendances = $parfumsRepository->findBy(['tendance' => true], null, 5);
        $coupsDeCoeur = $parfumsRepository->findBy(['coupDeCoeur' => true], null, 5);
        $totalItemsPanier = $panierItemParfumRepository->count([]);

        return $this->render('home/index.html.twig', [
            'coffrets' => $coffrets,
            'meilleursVente' => $meilleursVente,
            'tendances' => $tendances,
            'coupsDeCoeur' => $coupsDeCoeur,
            'totalItemsPanier' => $totalItemsPanier,

        ]);
    }
    #[Route('/cgu', name: 'cgu')]
    public function cgu(PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('conditions/CGU.html.twig', ['totalItemsPanier' => $totalItemsPanier,]);
    }

    #[Route('/cgv', name: 'cgv')]
    public function cgv(PanierItemParfumRepository $panierItemParfumRepository): Response
    {
        $totalItemsPanier = $panierItemParfumRepository->count([]);
        return $this->render('conditions/CGV.html.twig', ['totalItemsPanier' => $totalItemsPanier,]);
    }
}
