<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogComment;
use AppBundle\Entity\BlogPost;
use AppBundle\Form\BlogCommentType;
use AppBundle\Form\BlogPostType;
use AppBundle\Repository\BlogCommentRepository;
use AppBundle\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PostController extends Controller
{
  /**
   * @Route("/my-posts", name="my_posts")
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function myPostsAction(Request $request)
  {
    $page = max($request->query->getInt('page', 1), 1);
    /** @var BlogPostRepository $blogPostRepository */
    $blogPostRepository = $this->getDoctrine()->getRepository(BlogPost::class);
    $posts = $blogPostRepository->getAllPostsByUser($this->getUser(), $page, BlogPost::POST_LIMIT);
    $totalCount = $blogPostRepository->getPostCountByUser($this->getUser());

    //custom paginator
    $pagination = (object)[
      "current_page" => $page,
      "next_page" => $page + 1,
      "prev_page" => $page - 1,
      "has_next" => $totalCount > ($page * BlogPost::POST_LIMIT),
      "has_prev" => $page > 1,
      "total_count" => $totalCount,
    ];
    return $this->render('Post/my-posts.html.twig', [
      "posts" => $posts,
      "pagination" => $pagination
    ]);
  }

  /**
   * @Route("/posts/{slug}",name="view_post")
   * @param $slug
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function viewPostAction($slug, Request $request)
  {
    /** @var BlogPost $post */
    $post = $this->getDoctrine()
      ->getRepository(BlogPost::class)
      ->findOneBy(['slug' => $slug, 'draft' => false]);
    if (!$post) {
      $this->addFlash('danger', 'Unable to find post!');
      return $this->redirectToRoute('homepage');
    }

    $blogComment = new BlogComment();
    $form = $this->createForm(BlogCommentType::class, $blogComment);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //create BlogComment
      $em = $this->getDoctrine()->getManager();
      $blogComment->setCreatedAt(new \DateTime());
      $blogComment->setAuthor($this->getUser());
      $blogComment->setPost($post);
      $blogComment->setCreatedAt(new \DateTime());
      $em->persist($blogComment);
      $em->flush();
      $this->addFlash('success', 'Congratulations! Your comment is created');
      return $this->redirectToRoute('view_post', ["slug" => $post->getSlug()]);
    }

    /** @var BlogCommentRepository $commentsRepository */
    $commentsRepository = $this->getDoctrine()->getRepository(BlogComment::class);

    return $this->render('Post/view-post.html.twig', [
      "post" => $post,
      "comments" => $commentsRepository->getPostComments($post),
      "comment_form" => $form->createView(),
      "viewer" => $this->getUser(),
    ]);
  }

  /**
   * @Route("/new-post",name="new_post")
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function newPostAction(Request $request)
  {
    $post = new BlogPost();
    $form = $this->createForm(BlogPostType::class, $post);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //create BlogPost
      $em = $this->getDoctrine()->getManager();
      $post->setAuthor($this->getUser());
      $post->setCreatedAt(new \DateTime());
      $em->persist($post);
      $em->flush();
      $this->addFlash('success', 'Saved successfully!');
      return $this->redirectToRoute('view_post', ["slug" => $post->getSlug()]);
    }

    return $this->render('Post/new-post.html.twig', [
      "form" => $form->createView()
    ]);
  }

  /**
   * @Route("/edit-post/{slug}",name="edit_post")
   * @param $slug
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function editPostAction($slug, Request $request)
  {
    $post = $this->getDoctrine()
      ->getRepository(BlogPost::class)
      ->findOneBy(['slug' => $slug]);
    if (!$post) {
      $this->addFlash('danger', 'Unable to find post!');
      return $this->redirectToRoute('my_posts');
    }
    if ($post->getAuthor() !== $this->getUser()) {
      $this->addFlash('danger', 'You are cannot edit this post!');
      return $this->redirectToRoute('view_post', ["slug" => $post->getSlug()]);
    }

    $form = $this->createForm(BlogPostType::class, $post);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      //create BlogPost
      $em = $this->getDoctrine()->getManager();
      $em->persist($post);
      $em->flush();
      $this->addFlash('success', 'Saved successfully!');
      return $this->redirectToRoute('view_post', ["slug" => $post->getSlug()]);
    }
    return $this->render('Post/edit-post.html.twig', array(
      "form" => $form->createView(),
      "post" => $post,
    ));
  }

  /**
   * @Route("/delete-post/{slug}",name="delete_post")
   * @param $slug
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function deletePostAction($slug, Request $request)
  {
    /** @var BlogPost $post */
    $post = $this->getDoctrine()
      ->getRepository(BlogPost::class)
      ->findOneBy(['slug' => $slug]);
    if (!$post) {
      $this->addFlash('danger', 'Unable to find post!');
      return $this->redirectToRoute('my_posts');
    }
    if ($post->getAuthor() !== $this->getUser()) {
      $this->addFlash('danger', 'You are cannot delete this post!');
      return $this->redirectToRoute('view_post', ["slug" => $post->getSlug()]);
    }
    if ($request->getMethod() == 'POST') {
      $post->setDraft(true);
      $em = $this->getDoctrine()->getManager();
      $em->persist($post);
      $em->flush();
      $this->addFlash('success', 'Post was deleted!');
    } else {
      $this->addFlash('danger', 'Unable to remove post!');
    }

    return $this->redirectToRoute('my_posts');
  }

  /**
   * @Route("/delete-comment/{commentId}",name="delete_comment")
   * @param $commentId
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function deleteCommentAction($commentId, Request $request)
  {
    /** @var BlogComment $comment */
    $comment = $this->getDoctrine()
      ->getRepository(BlogComment::class)
      ->findOneBy(['id' => $commentId]);
    if (!$comment) {
      $this->addFlash('danger', 'Unable to find comment!');
      $params = $this->getRefererParams($request);
      return $this->redirect($this->generateUrl($params['_route'], ['slug' => $params['slug']]));
    }
    if (!($comment->getAuthor() == $this->getUser() || $comment->getPost()->getAuthor() == $this->getUser())) {
      $this->addFlash('danger', 'You are cannot delete this comment!');
      return $this->redirectToRoute('view_post', ["slug" => $comment->getPost()->getSlug()]);
    }
    if ($request->getMethod() == 'POST') {
      $comment->setDraft(true);
      $em = $this->getDoctrine()->getManager();
      $em->persist($comment);
      $em->flush();
      $this->addFlash('success', 'Comment was deleted!');
    } else {
      $this->addFlash('danger', 'Unable to remove comment!');
    }

    return $this->redirectToRoute('view_post', ["slug" => $comment->getPost()->getSlug()]);
  }

  private function getRefererParams(Request $request)
  {
    $referer = $request->headers->get('referer');
    $baseUrl = $request->getBaseUrl();
    $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));
    return $this->get('router')->getMatcher()->match($lastPath);
  }
}
