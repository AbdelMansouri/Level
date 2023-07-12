<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('nom')
      ->add('prenom')
      ->add('civilite', ChoiceType::class, [
        'choices' => [
          'Homme' => 'M',
          'Femme' => 'F',
        ],
      ])
      ->add('email')
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'invalid_message' => 'Les mots de passes ne correspondent pas',
        'first_options' => ['label' => 'Mot de passe'],
        'second_options' => ['label' => 'Confirmer le mot de passe'],
        'mapped' => false,
        'attr' => ['autocomplete' => 'new-password'],
        'constraints' => [
          new NotBlank([
            'message' => 'Merci de saisir un mot de passe',
          ]),
          new Length([
            'min' => 8,
            'minMessage' => 'Le mot de passe doit faire au minimum {{ limit }} caractères',
            'max' => 4096,
          ]),
          new Regex([
            'pattern' => '/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%#*?&]{8,}$/',
            'match' => true,
            'message' => 'Votre mot de passe doit contenir au moins un chiffre, un caractère spécial, une lettre en majuscule et et une en minuscule !'
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Membre::class,
    ]);
  }
}
