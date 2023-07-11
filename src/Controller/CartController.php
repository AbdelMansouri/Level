<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
  #[Route('/cart', name: 'app_cart')]
  public function index(CartService $cs): Response
  {
    $cartWithData = $cs->cartWithData();
    $total = $cs->total();
    $quantity = $cs->getQuantity();

    return $this->render('cart/cart.html.twig', [
      'items' => $cartWithData,
      'total' => $total,
      'quantity' => $quantity,
    ]);
  }

  // Pour ajouter un produit au panier (user)
  #[Route('/cart/add/{id}', name: 'cart_add')]
  public function add($id, Request $request, CartService $cs)
  {
    $quantite = $request->request->get('quantite', 1);
    $canAddToCart = $cs->canAddToCart($id, $quantite);

    if (!$canAddToCart['canAdd']) {
      $message = $canAddToCart['message'];
      return $this->redirectToRoute('product_details', ['id' => $id, 'message' => $message]);
    }

    $cs->add($id, $quantite);
    return $this->redirectToRoute('product_details', ['id' => $id]);
  }


  // Pour supprimer complètement un produit du panier (user)
  #[Route('/cart/remove/{id}', name: 'cart_remove')]
  public function remove($id, CartService $cs)
  {
    $cs->remove($id);
    return $this->redirectToRoute('app_cart');
  }

  // Pour diminuer la quantité du produit (user)
  #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
  public function decrease($id, CartService $cs)
  {
    $cs->decrease($id);
    return $this->redirectToRoute('app_cart');
  }

  // Pour augmenter la quantité du produit (user)
  #[Route('/cart/increase/{id}', name: 'cart_increase')]
  public function increase($id, CartService $cs, Request $request)
  {
    $result = $cs->increase($id);
    $message = $result['message'];

    return $this->redirectToRoute('app_cart', ['message' => $message]);
  }


  #[Route('/cart/confirm', name: 'cart_confirm')]
  public function commandConfirm(): Response
  {
    return $this->render('cart/confirm-cart.html.twig', []);
  }

  // Pour passer la commande
  #[Route('/cart/checkout', name: 'cart_checkout')]
  public function checkout(CartService $cs, EntityManagerInterface $entityManager): Response
  {

    if (!$this->getUser()) {
      return $this->redirectToRoute('app_login');
    }

    $cartItems = $cs->cartWithData();
    $user = $this->getUser();

    foreach ($cartItems as $item) {
      $commande = new Commande();
      $commande->setMembre($user);
      $commande->setProduit($item['product']);
      $commande->setQuantite($item['quantity']);
      $commande->setMontant($item['product']->getPrix() * $item['quantity']);
      $commande->setEtat('En cours');
      $commande->setDateEnregistrement(new \DateTime());
      $cs->decreaseStock($item['product']->getId(), $item['quantity']);

      $entityManager->persist($commande);
    }
    $cs->clearCart();
    $entityManager->flush();
    return $this->redirectToRoute('cart_confirm');
  }
}
