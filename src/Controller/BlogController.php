<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ObjectManager;
use App\Form\ArticleType;
/* use Symfony\Component\Form\Extension\Core\Type\FileType;
 */
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
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
     * @Route("/blog/create", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        /* 
       $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image') 
                    ->getForm();
 */
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request); //verification de la requete http

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush($article);

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
/*             'editMode' => $article->getId() 
 */        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/show.html.twig', [
            'controller_name' => 'BlogController',
            'article' => $article,
        ]);
    }
}
