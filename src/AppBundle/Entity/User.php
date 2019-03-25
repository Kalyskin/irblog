<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Exclude;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="This one is already taken")
 */
class User implements UserInterface
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="firstname", type="string", length=255)
   */
  private $firstname;

  /**
   * @var string
   *
   * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
   */
  private $lastname;

  /**
   * @var string
   *
   * @ORM\Column(name="email", type="string", length=255, unique=true)
   */
  private $email;

  /**
   * @var string
   *
   * @ORM\Column(name="password", type="string", length=255)
   * @Exclude
   */
  private $password;

  /**
   * @var boolean
   *
   * @ORM\Column(name="admin", type="boolean")
   * @Exclude
   */
  private $admin = false;

  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlogPost", mappedBy="author")
   * @Exclude
   */
  private $posts;

  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlogComment", mappedBy="author")
   * @Exclude
   */
  private $comments;

  /**
   * @var boolean
   *
   * @ORM\Column(name="draft", type="boolean")
   * @Exclude
   */
  private $draft = false;

  /**
   * @return bool
   */
  public function isDraft()
  {
    return $this->draft;
  }

  /**
   * @param bool $draft
   */
  public function setDraft($draft)
  {
    $this->draft = $draft;
  }

  public function __construct()
  {
    $this->posts = new ArrayCollection();
    $this->comments = new ArrayCollection();
  }

  public function __toString()
  {
    return trim($this->firstname." ".$this->lastname);
  }

  /**
   * @return Collection|BlogPost[]
   */
  public function getPosts()
  {
    return $this->posts;
  }

  /**
   * @return Collection|BlogComment[]
   */
  public function getComments()
  {
    return $this->comments;
  }

  /**
   * @return bool
   */
  public function isAdmin()
  {
    return $this->admin;
  }

  /**
   * @param bool $admin
   */
  public function setAdmin($admin)
  {
    $this->admin = $admin;
  }

  /**
   * Get id
   *
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set firstname
   *
   * @param string $firstname
   *
   * @return User
   */
  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;

    return $this;
  }

  /**
   * Get firstname
   *
   * @return string
   */
  public function getFirstname()
  {
    return $this->firstname;
  }

  /**
   * Set lastname
   *
   * @param string $lastname
   *
   * @return User
   */
  public function setLastname($lastname)
  {
    $this->lastname = $lastname;

    return $this;
  }

  /**
   * Get lastname
   *
   * @return string
   */
  public function getLastname()
  {
    return $this->lastname;
  }

  /**
   * Set email
   *
   * @param string $email
   *
   * @return User
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get email
   *
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set password
   *
   * @param string $password
   *
   * @return User
   */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Get password
   *
   * @return string
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   *
   * @return (Role|string)[] The user roles
   */
  public function getRoles()
  {
    if ($this->isAdmin())
      return ['ROLE_ADMIN'];
    return ['ROLE_USER'];;
  }

  /**
   * Returns the salt that was originally used to encode the password.
   *
   * This can return null if the password was not encoded using a salt.
   *
   * @return string|null The salt
   */
  public function getSalt()
  {
    return null;
  }

  /**
   * Returns the username used to authenticate the user.
   *
   * @return string The username
   */
  public function getUsername()
  {
    return (string)$this->email;
  }

  /**
   * Removes sensitive data from the user.
   *
   * This is important if, at any given point, sensitive information like
   * the plain-text password is stored on this object.
   */
  public function eraseCredentials()
  {
    // TODO: Implement eraseCredentials() method.
  }
}

