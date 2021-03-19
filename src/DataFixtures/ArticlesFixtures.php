<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Faker;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        
       
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

          
            for($j = 0; $j <= mt_rand(4,6); $j++){
                $article = new Article();

                
                $article->setTitle($faker->sentence())
                        ->setContent($faker->paragraph())
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 month'))
                        ->setCategory($category);
    
                        $manager->persist($article);
    
                for($k = 1; $k <= mt_rand(4,10); $k++){
                    $comment = new Comment();

                    $comment->setAuthor($faker->name)
                            ->setContent($faker->paragraph())
                            ->setCreatedAt($faker->dateTimeBetween('-6 month'))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
    
            }
        }

       

        $manager->flush();
    }
}
