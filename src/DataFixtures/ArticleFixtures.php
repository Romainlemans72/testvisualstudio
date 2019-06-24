<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture

{

    public function load(ObjectManager $manager)// on injecte (injection de dépendance) ObjectManager(ligne 7) dans la fonction et on lui attribue la variable $manager
    {
        $faker = \Faker\Factory::create('fr_FR') ;  
          //créer 3 categories manager
        //=> Créer 3 catégories faker en haut
        for($j =1;$j<=3;$j++){



            $category = new Category(); //=> mettre use

            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());

            $manager->persist($category);




            //=> Créer entre 4 et 6 articles
            for($i=1;$i<=mt_rand(4,6);$i++){
               

                $article = new Article();// article se retrouve est notre "Entity" et se retrouve dans Entity -> Article.php. Elle contient les éléments qui composent notre table que nous avons créee dans le terminal
                $content = '<p>'.join($faker->paragraphs(5),'</p><p>').'</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAd($faker->dateTimeBetween('- 6 months'))
                        ->setCategorie($category);
                        
    
                        $manager->persist($article);// persist = anciennement des "transactions" mise en mémoire et attend le "flush" qui est le fetchAll de symfony

        
                        //=> on donne des commentaires à l'article        
                        for($k=1;$k<=mt_rand(4,10);$k++){
                            $comment = new Comment();
                            $content = '<p>'.join($faker->paragraphs(2),'</p><p>').'</p>';
                            
                            $now = new \DateTime();
                            $interval = $now->diff($article->getCreatedAd());
                            $days = $interval->days;
                            $minimum = '-'.$days.' days'; //=> -100 days
                            $comment->setAuthor($faker->name)
                                    ->setContent($content)
                                    ->setCreatedAt($faker->dateTimeBetween($minimum))
                                    ->setArticle($article);
                    
                            $manager->persist($comment);
                        }
            }           
        }

        $manager->flush();
    }
}