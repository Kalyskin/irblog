<?php
/**
 * * Created by PhpStorm.
 * User: Kalys
 * Date: 22.03.19
 * Time: 22:17
 */

namespace App\Admin;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


final class UserAdmin extends AbstractAdmin
{
  private $passwordEncoder;

  public function __construct($code, $class, $baseControllerName, $passwordEncoder = null)
  {
    parent::__construct($code, $class, $baseControllerName);
    $this->passwordEncoder = $passwordEncoder;
  }

  /**
   * @param FormMapper $formMapper
   */
  protected function configureFormFields(FormMapper $formMapper)
  {
    $subject = $this->getSubject();

    $formMapper
      ->with("Main", ['class' => 'col-md-8'])
      ->add('firstname', TextType::class)
      ->add('lastname', TextType::class)
      ->add('email', EmailType::class);

    if (!$subject->getId()) {
      $formMapper->add("password", RepeatedType::class, [
        "type" => PasswordType::class,
        'first_options' => ['label' => 'Password'],
        'second_options' => ['label' => 'Repeat Password'],
      ]);
    }
    $formMapper
      ->end()
      ->with('Meta data', ['class' => 'col-md-4'])
      ->add("admin", CheckboxType::class, ['required' => false])
      ->add("draft", CheckboxType::class, ['required' => false])
      ->end();
  }

  /**
   * @param ListMapper $listMapper
   */
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('firstname')
      ->add('lastname')
      ->add('email')
      ->add('admin', 'boolean', [
        'editable' => true,
      ])
      ->add('draft', 'boolean', [
        'editable' => true,
      ]);
  }

  public function toString($object)
  {
    return $object instanceof User ? $object->getFirstname() : 'User';
  }

  public function prePersist($object)
  {
    /** @var User $object */
    $plainPassword = $object->getPassword();
    $container = $this->getConfigurationPool()->getContainer();
    $encoder = $container->get('security.password_encoder');
    $encoded = $encoder->encodePassword($object, $plainPassword);
    $object->setPassword($encoded);
  }


  /*public function preUpdate($object)
  {
    $container = $this->getConfigurationPool()->getContainer();
    $userRepository = $container->get('doctrine')->getRepository($this->getClass());
    $password = $userRepository->createQueryBuilder('u')
      ->select('u.password')
      ->where('u.email = :email')
      ->setParameter('email', $object->getEmail())
      ->getQuery()
      ->getSingleScalarResult();

    if ($password && !empty($object->getPassword()) && $password !== $object->getPassword()) {
      $encoder = $container->get('security.password_encoder');
      $encodedPass = $encoder->encodePassword($object, $object->getPassword());
      $object->setPassword($encodedPass);
    }
  }*/
}