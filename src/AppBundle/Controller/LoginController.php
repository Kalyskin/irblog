<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
  /**
   * @Route("/login",name="login")
   * @param Request $request
   * @param AuthenticationUtils $authenticationUtils
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
  {
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    if ($this->isGranted("IS_AUTHENTICATED_FULLY")) {
      return $this->redirectToRoute('homepage');
    }

    return $this->render('Login/login.html.twig', array(
      "error" => $error,
      "last_username" => $lastUsername,
    ));
  }

  /**
   * @Route("/register",name="register")
   * @param Request $request
   * @param UserPasswordEncoderInterface $encoder
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //create User
      $em = $this->getDoctrine()->getManager();
      $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('login');
    }
    return $this->render('Login/register.html.twig', array(
      "form" => $form->createView()
    ));
  }

  /**
   * @Route("/logout",name="logout")
   */
  public function logoutAction()
  {
    return $this->render('Login/logout.html.twig', array(// ...
    ));
  }

}
