<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;


class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        //Create 3 categories with Faker / Créer 3 categories avec Faker
        for($i = 1; $i <= 3; $i++)
        {
            $category = new Category();

            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            //Create between 4 and 6 articles / Créer en 4 et 6 articles 
            for($j = 1; $j <= mt_rand(4, 6); $j++)
            {
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(5), '<p></p>') .'</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);


                //We create comments for each article / On créé des commentaires pour chaque article
                for($k = 1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment();

                    $content = '<p>' . join($faker->paragraphs(2), '<p></p>') .'</p>';

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '- ' . $days . ' days';// - 100 days

                    $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);//Ce commentaire appartient à tel article

                    $manager->persist($comment);
                }
            }

            

        }

        $manager->flush();
    }
}
