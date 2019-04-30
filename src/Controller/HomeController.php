<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("create", name="create")
     */
    public function create(Request $request, ObjectManager $manager){
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTime());
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('show',['id' => $article->getId()
            ]);
        }
        
        return $this->render('home/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueilList(ArticleRepository $rep)
    {
        $article = $rep->findAll();
        return $this->render('home/accueil.html.twig',[
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(ArticleRepository $rep, $id, Request $request, ObjectManager $manager){
        $article = $rep->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTime());
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('show',['id' => $article->getId()
            ]);
        }
        
        return $this->render('home/show.html.twig',[
            'articles' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(){
        return $this->render('home/login.html.twig');
    }

     /**
     * @Route("/signup", name="signup")
     */
    public function signup(){
        return $this->render('home/signup.html.twig');
    }
}
