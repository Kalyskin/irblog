<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use AppBundle\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
  /**
   * @Route("/", name="homepage")
   */
  public function indexAction(Request $request)
  {
    $page = max($request->query->getInt('page', 1), 1);
    /** @var BlogPostRepository $blogPostRepository */
    $blogPostRepository = $this->getDoctrine()->getRepository(BlogPost::class);
    $posts = $blogPostRepository->getAllPosts($page, BlogPost::POST_LIMIT);
    $totalCount = $blogPostRepository->getPostCount();

    //custom paginator
    $pagination = (object)[
      "current_page" => $page,
      "next_page" => $page + 1,
      "prev_page" => $page - 1,
      "has_next" => $totalCount > ($page * BlogPost::POST_LIMIT),
      "has_prev" => $page > 1,
      "total_count" => $totalCount,
    ];
    return $this->render('default/index.html.twig', [
      "posts" => $posts,
      "pagination" => $pagination
    ]);
  }
}
