<?php

namespace App\Controller\Admin;

use App\Entity\Membre;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Service\DashboardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

class DashboardController extends AbstractDashboardController
{
  private DashboardService $dashboardService;

  public function __construct(DashboardService $dashboardService)
  {
    $this->dashboardService = $dashboardService;
  }

  #[Route('/admin', name: 'admin')]
  public function rendu(): Response
  {
    $dashboardData = $this->dashboardService->getDashboardData();

    return $this->render('admin/dashboard.html.twig', $dashboardData);
  }

  public function configureDashboard(): Dashboard
  {
    return Dashboard::new()
      ->setTitle('Level.')
      ->setTranslationDomain('EasyAdminBundle')
      ->setTextDirection('ltr');
  }

  public function configureMenuItems(): iterable
  {
    return [
      MenuItem::linkToRoute('Retour au site', 'fa fa-home', 'home'),
      MenuItem::linkToRoute('Tableau de bord', 'fas fa-chart-line', 'admin'),
      MenuItem::section('Gestion des produit'),
      MenuItem::linkToCrud('Produits', 'fas fa-shirt', Produit::class),
      MenuItem::section('Gestion des membres'),
      MenuItem::linkToCrud('Membres', 'fas fa-user', Membre::class),
      MenuItem::section('Gestion des commandes'),
      MenuItem::linkToCrud('Commandes', 'fas fa-box', Commande::class),
    ];
  }
}
