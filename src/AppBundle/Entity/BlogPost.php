<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\Exclude;

/**
 * BlogPost
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogPostRepository")
 */
class BlogPost
{
  const POST_LIMIT = 10;

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
   * @ORM\Column(name="title", type="string", length=255)
   */
  private $title;

  /**
   * @var string
   *
   * @Gedmo\Slug(fields={"title"})
   * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
   */
  private $slug;

  /**
   * @var string
   *
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   *
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="posts")
   */
  private $author;

  /**
   * @var boolean
   *
   * @ORM\Column(name="draft", type="boolean")
   * @Exclude
   */
  private $draft = false;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $createdAt;

  /**
   * @var string
   *
   * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
   * @Exclude
   */
  private $keywords;

  /**
   * @var string
   *
   * @ORM\Column(name="meta_description", type="text", nullable=true)
   * @Exclude
   */
  private $metaDescription;

  /**
   * @var integer
   *
   * @ORM\Column(name="rating", type="integer")
   */
  private $rating = 0;

  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\BlogComment", mappedBy="post")
   * @ORM\OrderBy({"createdAt" = "DESC"})
   * @Exclude
   */
  private $comments;

  public function __construct()
  {
    $this->comments = new ArrayCollection();
  }

  /**
   * @return Collection|BlogComment[]
   */
  public function getComments()
  {
    return $this->comments;
  }

  /**
   * @return int
   */
  public function getRating()
  {
    return $this->rating;
  }

  /**
   * @param int $rating
   */
  public function setRating($rating)
  {
    $this->rating = $rating;
  }

  /**
   * @return bool
   * @Exclude
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
   * Set title
   *
   * @param string $title
   *
   * @return BlogPost
   */
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * Get title
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set slug
   *
   * @param string $slug
   *
   * @return BlogPost
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;

    return $this;
  }

  /**
   * Get slug
   *
   * @return string
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * Set content
   *
   * @param string $content
   *
   * @return BlogPost
   */
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * Get content
   *
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set author
   *
   * @param integer $author
   *
   * @return BlogPost
   */
  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  /**
   * Get author
   *
   * @return User
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * Set createdAt
   *
   * @param \DateTime $createdAt
   *
   * @return BlogPost
   */
  public function setCreatedAt($createdAt)
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get createdAt
   *
   * @return \DateTime
   */
  public function getCreatedAt()
  {
    return $this->createdAt;
  }

  /**
   * Set keywords
   *
   * @param string $keywords
   *
   * @return BlogPost
   */
  public function setKeywords($keywords)
  {
    $this->keywords = $keywords;

    return $this;
  }

  /**
   * Get keywords
   *
   * @return string
   */
  public function getKeywords()
  {
    return $this->keywords;
  }

  /**
   * Set metaDescription
   *
   * @param string $metaDescription
   *
   * @return BlogPost
   */
  public function setMetaDescription($metaDescription)
  {
    $this->metaDescription = $metaDescription;

    return $this;
  }

  /**
   * Get metaDescription
   *
   * @return string
   */
  public function getMetaDescription()
  {
    return $this->metaDescription;
  }

  public function __toString()
  {
    return $this->title;
  }

  /**
   * @ORM\PrePersist
   */
  public function prePersist()
  {
    if (!$this->getCreatedAt()) {
      $this->setCreatedAt(new \DateTime());
    }
  }
}

