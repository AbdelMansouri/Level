<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Commande::class;
  }

  public function configureFields(string $pageName): iterable
  {
    return [
      IdField::new('id')->hideOnForm(),
      AssociationField::new('membre')->hideOnForm(),
      AssociationField::new('produit')->hideOnForm(),
      IntegerField::new('quantite')->setDisabled(true),
      NumberField::new('montant')->setDisabled(true),
      ChoiceField::new('etat')->setChoices([
        'En cours de traitement' => 'En cours',
        'Envoyé' => 'Envoyé',
        'Livré' => 'Livré',
      ]),
      DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m')->setDisabled(true),
    ];
  }

  public function configureActions(Actions $actions): Actions
  {
    return $actions
      ->disable(Action::NEW);
  }
}
