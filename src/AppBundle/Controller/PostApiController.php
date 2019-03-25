<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use AppBundle\Entity\User;
use AppBundle\Repository\BlogPostRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class PostApiController extends FOSRestController
{
  /**
   * @Rest\Get("/api/posts")
   * @param Request $request
   * @return array|View
   */
  public function getAction(Request $request)
  {
    $page = max($request->query->getInt('page', 1), 1);
    /** @var BlogPostRepository $blogPostRepository */
    $blogPostRepository = $this->getDoctrine()->getRepository(BlogPost::class);
    $posts = $blogPostRepository->getAllPosts($page, BlogPost::POST_LIMIT);
    if (empty($posts)) {
      return new View("there are no posts exist", Response::HTTP_NOT_FOUND);
    }
    return $posts;
  }

  /**
   * @Rest\Get("/api/post/{id}")
   * @param $id
   * @return View|null|object
   */
  public function idAction($id)
  {
    $singleResult = $this->getDoctrine()
      ->getRepository(BlogPost::class)
      ->findOneBy([
        "id" => $id,
        "draft" => false
      ]);
    if ($singleResult === null) {
      return new View("post not found", Response::HTTP_NOT_FOUND);
    }
    return $singleResult;
  }

  /**
   * @Rest\Post("/api/post/")
   * @param Request $request
   * @return View
   */
  public function postAction(Request $request)
  {
    $data = new BlogPost();
    $title = $request->get('title');
    $content = $request->get('content', '');
    $keywords = $request->get('keywords', '');
    $meta_description = $request->get('meta_description', '');
    $author = $this->getUser();
    if (empty($title) || empty($content)) {
      return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
    }
    $data->setTitle($title);
    $data->setContent($content);
    $data->setAuthor($author);
    $data->setKeywords($keywords);
    $data->setMetaDescription($meta_description);
    $em = $this->getDoctrine()->getManager();
    $em->persist($data);
    $em->flush();
    return new View("Post Added Successfully", Response::HTTP_OK);
  }

  /**
   * @Rest\Put("/api/post/{id}")
   * @param $id
   * @param Request $request
   * @return View
   */
  public function updateAction($id, Request $request)
  {
    $sn = $this->getDoctrine()->getManager();
    /** @var BlogPost $post */
    $post = $this->getDoctrine()
      ->getRepository(BlogPost::class)
      ->findOneBy([
        "id" => $id,
        "draft" => false
      ]);
    if (empty($post)) {
      return new View("post not found", Response::HTTP_NOT_FOUND);
    }
    /** @var User $author */
    $author = $this->getUser();
    if ($post->getAuthor()->getId() !== $author->getId()) {
      return new View("Access denied", Response::HTTP_FORBIDDEN);
    }
    $title = $request->get('title', $post->getTitle());
    $content = $request->get('content', $post->getContent());
    $keywords = $request->get('keywords', $post->getKeywords());
    $meta_description = $request->get('meta_description', $post->getMetaDescription());

    $post->setTitle($title);
    $post->setContent($content);
    $post->setKeywords($keywords);
    $post->setMetaDescription($meta_description);
    $sn->flush();
    return new View("Post Updated Successfully", Response::HTTP_OK);
  }

  /**
   * @Rest\Delete("/api/post/{id}")
   * @param $id
   * @return View
   */
  public function deleteAction($id)
  {
    $sn = $this->getDoctrine()->getManager();
    /** @var BlogPost $post */
    $post = $this->getDoctrine()->getRepository(BlogPost::class)->find($id);
    if (empty($post)) {
      return new View("post not found", Response::HTTP_NOT_FOUND);
    }
    /** @var User $author */
    $author = $this->getUser();
    if ($post->getAuthor()->getId() !== $author->getId()) {
      return new View("Access denied", Response::HTTP_FORBIDDEN);
    }
    $post->setDraft(true);
    $sn->flush();
    return new View("deleted successfully", Response::HTTP_OK);
  }
}
