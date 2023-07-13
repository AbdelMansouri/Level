<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
  private $repo;
  private $rs;
  private $entityManager;

  public function __construct(ProduitRepository $repo, RequestStack $rs, EntityManagerInterface $entityManager)
  {
    $this->rs = $rs;
    $this->repo = $repo;
    $this->entityManager = $entityManager;
  }

  // Pour l'ajout au panier, création de la session
  public function add($id, $quantite)
  {
    $session = $this->rs->getSession();

    $cart = $session->get('cart', []);
    $qt = $session->get('qt', 0);

    if (!empty($cart[$id])) {
      $cart[$id] += $quantite;
      $qt += $quantite;
    } else {
      $cart[$id] = $quantite;
      $qt += $quantite;
    }

    $session->set('cart', $cart);
    $session->set('qt', $qt);
  }

  // Pour la supression complète du panier
  public function remove($id)
  {
    $session = $this->rs->getSession();
    $cart = $session->get('cart', []);
    $qt = $session->get('qt', 0);

    if (!empty($cart[$id])) {
      $qt -= $cart[$id];
      unset($cart[$id]);
    }

    if ($qt < 0) {
      $qt = 0;
    }

    $session->set('cart', $cart);
    $session->set('qt', $qt);
  }

  // Récupération des infos du panier pour l'affichage utilisateur
  public function cartWithData()
  {
    $session = $this->rs->getSession();
    $cart = $session->get('cart', []);

    $cartWithData = [];

    foreach ($cart as $id => $quantity) {
      $produit = $this->repo->find($id);
      $cartWithData[] = [
        'product' => $produit,
        'quantity' => $quantity
      ];
    }
    return $cartWithData;
  }

  // Quantité totale
  public function getQuantity()
  {
    $session = $this->rs->getSession();
    return $session->get('qt', 0);
  }

  // Compte le prix total du panier
  public function total()
  {
    $cartWithData = $this->cartWithData();
    $total = 0;
    foreach ($cartWithData as $item) {
      $totalItem = $item['product']->getPrix() * $item['quantity'];
      $total += $totalItem;
    }
    return $total;
  }

  // Le stock est-il suffisant ?
  public function canAddToCart($id, $quantite)
  {
    $produit = $this->repo->find($id);
    $stock = $produit->getStock();
    $quantiteDejaDansLePanier = $this->getQuantityById($id);

    $quantiteTotale = $quantite + $quantiteDejaDansLePanier;

    $result = [
      'canAdd' => $stock >= $quantiteTotale,
      'message' => $stock >= $quantiteTotale ? null : 'Stock insuffisant pour ajouter au panier.',
    ];

    return $result;
  }

  private function getQuantityById($id)
  {
    $session = $this->rs->getSession();
    $cart = $session->get('cart', []);
    return $cart[$id] ?? 0;
  }


  // Pour diminuer le stock lors de la commande
  public function decreaseStock($id, $quantity)
  {
    $produit = $this->repo->find($id);
    $stock = $produit->getStock();
    $newStock = $stock - $quantity;
    $produit->setStock($newStock);
    $this->entityManager->flush();
  }

  // Pour la diminution de la quantité à commander (user)
  public function decrease($id)
  {
    $session = $this->rs->getSession();
    $cart = $session->get('cart', []);
    $qt = $session->get('qt', 0);

    if (!empty($cart[$id])) {
      $cart[$id]--;
      $qt--;
      if ($cart[$id] <= 0) {
        unset($cart[$id]);
      }
    }

    if ($qt < 0) {
      $qt = 0;
    }

    $session->set('cart', $cart);
    $session->set('qt', $qt);
  }

  // Pour augmenter de la quantité à commander (user)
  private function canIncreaseQuantity($id, $quantity)
  {
    $produit = $this->repo->find($id);
    $stock = $produit->getStock();

    $quantiteTotale = $quantity + 1;

    $result = [
      'canIncrease' => $stock >= $quantiteTotale,
      'message' => $stock >= $quantiteTotale ? null : 'Stock insuffisant pour augmenter la quantité.'
    ];

    return $result;
  }

  public function increase($id)
  {
    $session = $this->rs->getSession();
    $cart = $session->get('cart', []);
    $qt = $session->get('qt', 0);

    $canIncrease = $this->canIncreaseQuantity($id, $cart[$id]);

    if ($canIncrease['canIncrease']) {
      $cart[$id]++;
      $qt++;
    }

    $session->set('cart', $cart);
    $session->set('qt', $qt);

    return $canIncrease;
  }



  // Pour vider la session après commande
  public function clearCart()
  {
    $session = $this->rs->getSession();
    $session->remove('cart');
    $session->remove('qt');
  }
}
