<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostType extends AbstractType
{
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title')
      //->add('slug')
      ->add('content')
      //->add('draft')
      //->add('createdAt')
      ->add('keywords')
      ->add('metaDescription')
      ->add("submit", SubmitType::class, [
        "attr" => [
          "class" => "btn btn-success pull-right"
        ]
      ]);
      //->add('rating')
      //->add('author')
    ;
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\BlogPost'
    ));
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
    return 'appbundle_blogpost';
  }


}
