<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Produit::class;
  }


  public function configureFields(string $pageName): iterable
  {
    return [
      IdField::new('id')->hideOnForm(),
      TextField::new('titre'),
      TextField::new('description'),
      ChoiceField::new('couleur')->setChoices([
        'Bleu' => 'bleu',
        'Jaune' => 'jaune',
        'Vert' => 'vert',
        'Noir' => 'noir',
        'Blanc' => 'blanc',
        'Rouge' => 'rouge',
      ]),
      ChoiceField::new('taille')->setChoices([
        'XS' => 'xs',
        'S' => 's',
        'M' => 'm',
        'L' => 'l',
        'XL' => 'xl',
        'XXL' => 'xxl',
      ]),
      ChoiceField::new('collection')->renderExpanded()->setChoices([
        'Homme' => 'h',
        'Femme' => 'f',
      ]),
      ImageField::new('photo')->setBasePath('assets/upload')->hideOnForm(),
      ImageField::new('photo')->setUploadDir('public/assets/upload')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenCreating(),
      ImageField::new('photo')->setUploadDir('public/assets/upload')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenUpdating()->setFormTypeOption('required', false),
      NumberField::new('prix', 'Prix'),
      IntegerField::new('stock')->setFormTypeOptions(['attr' => ['min' => 0]]),
      DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m')->setDisabled(true),
    ];
  }

  public function createEntity(string $entityFqcn)
  {
    $produit = new $entityFqcn;
    $produit->setDateEnregistrement(new DateTime);
    return $produit;
  }
}
