<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogComment;
use AppBundle\Entity\BlogPost;
use AppBundle\Entity\User;
use AppBundle\Repository\BlogCommentRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CommentApiController extends FOSRestController
{
  /**
   * @Rest\Get("/api/comments/{postId}")
   * @param $postId
   * @param Request $request
   * @return array|View
   */
  public function getAction($postId, Request $request)
  {
    /** @var BlogPost $post */
    $post = $this->getDoctrine()->getRepository(BlogPost::class)->find((int)$postId);
    if (!$post) {
      return new View("post not found", Response::HTTP_NOT_FOUND);
    }
    $page = max($request->query->getInt('page', 1), 1);
    /** @var BlogCommentRepository $blogCommentRepository */
    $blogCommentRepository = $this->getDoctrine()->getRepository(BlogComment::class);
    $comments = $blogCommentRepository->getPostComments($post, $page, 100);

    if (empty($comments)) {
      return new View("there are no comments exist", Response::HTTP_NOT_FOUND);
    }
    return $comments;
  }

  /**
   * @Rest\Get("/api/comment/{id}")
   * @param $id
   * @return View|null|object
   */
  public function idAction($id)
  {
    $singleResult = $this->getDoctrine()
      ->getRepository(BlogComment::class)
      ->findOneBy([
        "id" => $id,
        "draft" => false
      ]);
    if ($singleResult === null) {
      return new View("comment not found", Response::HTTP_NOT_FOUND);
    }
    return $singleResult;
  }

  /**
   * @Rest\Post("/api/comment/")
   * @param Request $request
   * @return View
   */
  public function postAction(Request $request)
  {
    $data = new BlogComment();
    $content = $request->get('content');
    $author = $this->getUser();
    if (empty($content)) {
      return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
    }
    if (empty($author)) {
      return new View("Access denied", Response::HTTP_FORBIDDEN);
    }
    $data->setContent($content);
    $data->setAuthor($author);
    $data->setCreatedAt(new \DateTime());
    $em = $this->getDoctrine()->getManager();
    $em->persist($data);
    $em->flush();
    return new View("Comment Added Successfully", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/api/comment/{id}")
   * @param $id
   * @param Request $request
   * @return View
   */
  public function updateAction($id, Request $request)
  {
    $sn = $this->getDoctrine()->getManager();
    /** @var BlogPost $comment */
    $comment = $this->getDoctrine()
      ->getRepository(BlogComment::class)
      ->findOneBy([
        "id" => $id,
        "draft" => false
      ]);
    if (empty($comment)) {
      return new View("comment not found", Response::HTTP_NOT_FOUND);
    }
    $content = $request->get('content');
    if (empty($content)) {
      return new View("content is empty", Response::HTTP_NOT_FOUND);
    }
    /** @var User $author */
    $author = $this->getUser();
    if (!$author || $comment->getAuthor()->getId() !== $author->getId()) {
      return new View("Access denied", Response::HTTP_FORBIDDEN);
    }
    $comment->setContent($content);
    $sn->flush();
    return new View("Comment Updated Successfully", Response::HTTP_OK);
  }

  /**
   * @Rest\Delete("/api/comment/{id}")
   * @param $id
   * @return View
   */
  public function deleteAction($id)
  {
    $sn = $this->getDoctrine()->getManager();
    /** @var BlogPost $comment */
    $comment = $this->getDoctrine()->getRepository(BlogComment::class)->find($id);
    if (empty($comment)) {
      return new View("comment not found", Response::HTTP_NOT_FOUND);
    }
    /** @var User $author */
    $author = $this->getUser();
    if (!$author || $comment->getAuthor()->getId() !== $author->getId()) {
      return new View("Access denied", Response::HTTP_FORBIDDEN);
    }
    $comment->setDraft(true);
    $sn->flush();
    return new View("deleted successfully", Response::HTTP_OK);
  }
}
