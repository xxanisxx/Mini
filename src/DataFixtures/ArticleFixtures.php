<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1; $i <=4 ; $i++) { 
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
            
            $manager->persist($category);

            for ($j=0; $j < 4; $j++) { 

                $article = new Article();
                $content =  join($faker->paragraphs(5));
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);

                for ($k=1; $k <= 5; $k++) { 
                    $comment = new Comment();
                    $content = join($faker->paragraphs(2));
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-'.$days.'days'))
                            ->setArticle($article);

                            $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
