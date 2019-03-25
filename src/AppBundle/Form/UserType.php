<?php
/**
 * * Created by PhpStorm.
 * User: Kalys
 * Date: 21.03.19
 * Time: 21:52
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $formBuilder, array $options)
  {
    $formBuilder
      ->add("firstname", TextType::class)
      ->add("lastname", TextType::class)
      ->add("email", EmailType::class)
      ->add("password", RepeatedType::class, [
        "type" => PasswordType::class,
        'first_options'  => ['label' => 'Password'],
        'second_options' => ['label' => 'Repeat Password'],
      ])
      ->add("submit", SubmitType::class, [
        "attr" => [
          "class" => "btn btn-success pull-right"
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      "data_class" => User::class
    ]);
  }
}