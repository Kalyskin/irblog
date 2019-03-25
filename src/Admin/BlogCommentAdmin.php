<?php
/**
 * * Created by PhpStorm.
 * User: Kalys
 * Date: 22.03.19
 * Time: 22:17
 */

namespace App\Admin;

use AppBundle\Entity\BlogComment;
use AppBundle\Entity\BlogPost;
use AppBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Knp\Menu\ItemInterface as MenuItemInterface;


final class BlogCommentAdmin extends AbstractAdmin
{
  /**
   * @param FormMapper $formMapper
   */
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->with('Content', ['class' => 'col-md-8'])
      ->add('content', TextareaType::class)
      ->add("created_at", DateTimeType::class)
      ->end()
      ->with('Meta data', ['class' => 'col-md-4'])
      ->add('author', EntityType::class, [
        'class' => User::class,
      ])
      ->add('post', EntityType::class, [
        'class' => BlogPost::class,
        'choice_label' => 'title'
      ])
      ->end();

  }

  /**
   * @param ListMapper $listMapper
   */
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('author')
      ->add('post')
      ->add('content')
      ->add('draft', 'boolean',[
        'editable' => true,
      ])
      ->add('created_at', 'datetime', array('date_format' => 'yyyy-MM-dd HH:mm:ss'));
  }

  public function toString($object)
  {
    return $object instanceof BlogComment ? $object->getId() : 'Blog Comment';
  }
}