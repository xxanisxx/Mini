<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\CommentLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($e = 0; $e < 20; $e++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setUsername($faker->userName)
                ->setPassword($this->encoder->encodePassword($user, 'anisanis'));

            $manager->persist($user);
            $users[] = $user;
            $usernames[] = $user->getUsername();
        }

        for ($i = 1; $i <= 4; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());

            $manager->persist($category);


            for ($j = 0; $j < 4; $j++) {

                $article = new Article();
                $content =  join($faker->paragraphs(5));
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category)
                    ->setUser($faker->randomElement($users));
                $manager->persist($article);



                for ($k = 1; $k <= 5; $k++) {
                    $comment = new Comment();
                    $content = join($faker->paragraphs(2));
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->randomElement($usernames))
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                        ->setArticle($article)
                        ->setUser($faker->randomElement($users));

                    $manager->persist($comment);

                    for ($l = 0; $l < mt_rand(0, 20); $l++) {
                        $like = new CommentLike();
                        $like->setUser($faker->randomElement($users))
                            ->setComment($comment);
                        $manager->persist($like);
                    }
                }
            }
        }

        $manager->flush();
    }
}
