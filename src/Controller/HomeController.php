<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
  #[Route('/', name: 'home')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $query = $entityManager->createQuery(
      'SELECT p
      FROM App\Entity\Produit p
      WHERE p.stock > 2
      ORDER BY p.stock ASC'
    )->setMaxResults(4);

    $produits = $query->getResult();

    return $this->render('home/index.html.twig', [
      'is_homepage' => true,
      'produits' => $produits
    ]);
  }
}
