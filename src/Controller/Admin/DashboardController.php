<?php

namespace App\Controller\Admin;

use App\Repository\PanierRepository;
use App\Repository\ParfumsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/admin', name: 'admin_')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'dashboard')]
    public function index(UserRepository $userRepository, ParfumsRepository $parfumsRepository, PanierRepository $panierRepository): Response
    {
$totalUsers = $userRepository->count([]);
$totalParfums = $parfumsRepository->count([]);
$totalCoffrets= $parfumsRepository->count(['isCoffret'=>true]);
$totalPaniers= $panierRepository->count([]);


        return $this->render('admin/dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalParfums' => $totalParfums,
            'totalCoffrets' =>$totalCoffrets,
            'totalPaniers' => $totalPaniers,
        ]);
    }
}
