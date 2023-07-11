<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
  #[Route('/shop', name: 'shop')]
  public function index(ProduitRepository $repo): Response
  {
    $produits = $repo->findAll();

    return $this->render('shop/index.html.twig', [
      'produits' => $produits
    ]);
  }

  #[Route('/shop/{collection}', name: 'shop_collection')]
  public function filterByCollection(ProduitRepository $repo, string $collection): Response
  {
    $produits = $repo->findByCollection($collection);

    return $this->render('shop/index.html.twig', [
      'produits' => $produits
    ]);
  }

  #[Route('/shop/product/{id}', name: 'product_details')]
  public function detailsProduit(Produit $produit, Request $request)
  {
      $message = $request->query->get('message');
  
      return $this->render('shop/product-details.html.twig', [
          'produit' => $produit,
          'message' => $message,
      ]);
  }
  
}
