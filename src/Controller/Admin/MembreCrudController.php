<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Membre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MembreCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Membre::class;
  }


  public function configureFields(string $pageName): iterable
  {
    return [
      IdField::new('id')->hideOnForm(),
      ChoiceField::new('civilite')->renderExpanded()->setChoices([
        'Homme' => 'H',
        'Femme' => 'F',
      ]),
      TextField::new('nom'),
      TextField::new('prenom'),
      TextField::new('email'),
      TextField::new('password', 'mot de passe')->setFormType(PasswordType::class)->onlyWhenCreating(),
      CollectionField::new('roles')->setTemplatePath('/admin/field/roles.html.twig'),
      DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m')->setDisabled(true),
    ];
  }

  public function createEntity(string $entityFqcn)
  {
    $membre = new $entityFqcn;
    $membre->setDateEnregistrement(new DateTime);
    return $membre;
  }

  public function configureActions(Actions $actions): Actions
  {
    return $actions
      ->disable(Action::NEW);
  }
}
