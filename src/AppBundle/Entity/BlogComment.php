<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * BlogComment
 *
 * @ORM\Table(name="blog_comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BlogCommentRepository")
 */
class BlogComment
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
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="comments")
   */
  private $author;

  /**
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BlogPost", inversedBy="comments")
   * @ORM\JoinColumn(onDelete="CASCADE")
   * @Exclude
   */
  private $post;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="created_at", type="datetime")
   */
  private $createdAt;

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
   * Set content
   *
   * @param string $content
   *
   * @return BlogComment
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
   * @return BlogComment
   */
  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  /**
   * Get author
   *
   * @return int
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * Set post
   *
   * @param BlogPost $post
   *
   * @return BlogComment
   */
  public function setPost($post)
  {
    $this->post = $post;

    return $this;
  }

  /**
   * Get post
   *
   * @return BlogPost
   */
  public function getPost()
  {
    return $this->post;
  }

  /**
   * Set createdAt
   *
   * @param \DateTime $createdAt
   *
   * @return BlogComment
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
   * @ORM\PrePersist
   */
  public function prePersist()
  {
    if (!$this->getCreatedAt()) {
      $this->setCreatedAt(new \DateTime());
    }
  }
}

