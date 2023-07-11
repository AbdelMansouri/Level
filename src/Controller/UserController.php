<?php

namespace App\Controller;

use App\Repository\MembreRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
  #[Route('/user', name: 'user')]
  public function userInfos(CommandeRepository $repo)
  {
    $membre = $this->getUser();
    $commandes = $repo->findBy(['membre' => $this->getUser()]);
    $commandesCount = count($commandes);
    return $this->render('user/index.html.twig', [
      'membre' => $membre,
      'commandes' => $commandes,
      'commandesCount' => $commandesCount,
    ]);
  }
}
