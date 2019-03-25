<?php
/**
 * * Created by PhpStorm.
 * User: Kalys
 * Date: 25.03.19
 * Time: 19:46
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;
use AppBundle\Entity\BlogPost;
use AppBundle\Entity\BlogComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
  /** @var UserPasswordEncoderInterface */
  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
  {
    $this->encoder = $encoder;
  }

  public function load(ObjectManager $manager)
  {
    $user = new User();
    $user->setFirstname('Admin');
    $user->setLastname('Irblog');
    $user->setEmail('admin@example.com');
    $user->setAdmin(true);
    $user->setDraft(false);
    $user->setPassword($this->encoder->encodePassword($user, 'admin'));
    $manager->persist($user);
    $manager->flush();

    $post = new BlogPost();
    $post->setTitle("First post");
    $post->setContent("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis dolorum expedita ipsam quo repellat. Adipisci aliquid aperiam architecto at aut, commodi consectetur delectus dignissimos dolore expedita impedit, mollitia nesciunt odio pariatur perferendis possimus quae qui quia, quisquam quos rem saepe vel vero. Aperiam delectus eaque exercitationem libero maiores perspiciatis tempore! Ab accusamus animi dicta explicabo facilis fugiat officia quis recusandae, temporibus tenetur unde veniam. Animi aperiam at atque aut consectetur consequuntur deserunt eligendi fugit, itaque, quod reprehenderit rerum sed tempore. Dolorem, nam, natus. Ad, blanditiis dignissimos doloribus ea expedita facere hic illum impedit incidunt iusto maiores nihil numquam officia porro quod reiciendis voluptates? Adipisci deleniti earum illum magnam minus nihil ullam. Aspernatur, enim necessitatibus nisi odio officiis quisquam sunt veniam.");
    $post->setAuthor($user);
    $post->setCreatedAt(new \DateTime());
    $manager->persist($post);
    $manager->flush();

    $comment = new BlogComment();
    $comment->setAuthor($user);
    $comment->setPost($post);
    $comment->setContent("My first comment");
    $comment->setCreatedAt(new \DateTime());
    $manager->persist($comment);
    $manager->flush();
  }
}