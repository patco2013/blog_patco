<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    
    // Show all articles / Affiche tous les articles 
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    public function formArt(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$article)
        {
            $article = new Article();
        }
       
        /*$form = $this->createFormBuilder($article)
                        ->add('title')
                        ->add('content')
                        ->add('image')
                        ->getForm();*/

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);//analyse la requête http

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId())//si l'article n'a pas d'identifiant
            {
                $article->setCreatedAt(new \DateTime());//On met une date de création
            }
            
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }               

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    //Show an article / Affiche un article
    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}
