<?php

namespace App\Controller;



use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class BlogController extends AbstractController
{


    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        
        return $this->render('blog/index.html.twig',[
            'articles'=>$articles

        ]);
    }

    /**
     * @Route("/", name="home") si dans route on met un simple / cela va être lu comme étant la page par défaut par symfony
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }


/**
* @Route("/blog/new", name="blog_create")
* @Route("/blog/{id}/edit", name= "blog_edit")
*/
public function create(Article $article = null, Request $request, ObjectManager $manager){ // injection de dépendance Request (get, post etc...), object manager

    if(!$article){
        $article = new Article ;
    }
    

    
    $form = $this->createForm(ArticleType::class, $article);

    
	 // on fait appel à l'entité "Article" ->la table de la bdd

	//$form = $this->createFormBuilder($article)
	//	->add("title")
	//    ->add('content')
	//	->add('image')
    //    ->getForm();
        
       

       if(!$article->getId()){
           $article->setCreatedAd(new \DateTime());
            }
              
        
        $form->handleRequest($request);
dump($article);

        if($form->isSubmitted() && $form->isValid()){      //"SI formulaire est envoyé et SI il est valide fait partir    
            $manager->persist($article); // persist est un peu comme le "base->prepare"
            $manager->flush();
            return $this->redirectToRoute('blog_show' , ['id'=>$article->getId()]) ;

            //ici, on demande à symfony d'ouvrir l'article qu'on vient de crééer au moment de le submit via ->redirectToRoute. On y ajoute le name de la page vers quoi on veut aller name="blog_show" (notre fonction show sur ce controller avec le name)
        }

	return $this->render('blog/create.html.twig',[
    'formArticle'=>$form->createView(),
    'editMode'=>$article->getId()
	]) ;

}

      /**
     * @Route("blog/{id}", name="blog_show")
     *      
     */
    public function show($id)
    
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        return $this->render('blog/show.html.twig', [
            'article'=>$article

        ]);
    }

    


}
