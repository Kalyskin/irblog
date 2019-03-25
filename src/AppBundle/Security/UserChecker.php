<?php
/**
 * * Created by PhpStorm.
 * User: Kalys
 * Date: 24.03.19
 * Time: 19:06
 */

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
  /**
   * @param UserInterface $user
   * @throws \Exception
   */
  public function checkPreAuth(UserInterface $user)
  {
    if (!$user instanceof User) {
      return;
    }
    if ($user->isDraft()) {
      throw new CustomUserMessageAuthenticationException(
        'Your account was deleted. Sorry about that!'
      );
    }
  }

  /**
   * @param UserInterface $user
   */
  public function checkPostAuth(UserInterface $user)
  {
    if (!$user instanceof User) {
      return;
    }

    if ($user->isDraft()) {
      throw new CustomUserMessageAuthenticationException(
        'Your account was deleted. Sorry about that!'
      );
    }
  }
}