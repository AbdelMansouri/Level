<?php

namespace App\Service;

use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class DashboardService
{
  private EntityManagerInterface $entityManager;
  private MembreRepository $membreRepository;
  private CommandeRepository $commandeRepository;
  private ProduitRepository $produitRepository;

  public function __construct(
    EntityManagerInterface $entityManager,
    MembreRepository $membreRepository,
    CommandeRepository $commandeRepository,
    ProduitRepository $produitRepository
  ) {
    $this->entityManager = $entityManager;
    $this->membreRepository = $membreRepository;
    $this->commandeRepository = $commandeRepository;
    $this->produitRepository = $produitRepository;
  }

  public function getDashboardData(): array
  {
    // Récupérer le nombre total de membres
    $nombreMembres = $this->membreRepository->count([]);

    // Récupérer le nombre total de commandes
    $nombreCommandes = $this->commandeRepository->count([]);

    // Récupérer le nombre total de produits
    $nombreProduits = $this->produitRepository->count([]);

    // Récupérer tous les produits
    $produits = $this->produitRepository->findAll();

    $prixTotal = 0;
    foreach ($produits as $produit) {
      // Calculer le prix total de tous les produits
      $prixTotal += (float) $produit->getPrix();
    }

    // Calculer le prix moyen des produits
    $prixMoyen = $nombreProduits > 0 ? $prixTotal / $nombreProduits : 0;

    // Calculer le panier moyen global
    $panierMoyenGlobal = $this->commandeRepository->createQueryBuilder('c')
      ->select('AVG(p.prix) as panierMoyen')
      ->join('c.produit', 'p')
      ->getQuery()
      ->getSingleScalarResult();

      
    $commandes = $this->commandeRepository->findAll();
    $chiffreAffaire = 0;
    foreach ($commandes as $commande) {
      $chiffreAffaire += $commande->getMontant();
    }

    // Retourner les données du dashboard sous forme de tableau associatif
    return [
      'chiffreAffaire' => $chiffreAffaire,
      'nombreCommandes' => $nombreCommandes,
      'panierMoyenGlobal' => $panierMoyenGlobal,
      'nombreMembres' => $nombreMembres,
      'nombreProduits' => $nombreProduits,
      'prixMoyen' => $prixMoyen,
      'produits' => $produits,
    ];
  }
}
