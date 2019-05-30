<?php

namespace App\Controller;

use App\Entity\Comment;

use App\Form\CommentType;
use App\Entity\CommentLike;
use App\Repository\ArticleRepository;
use App\Repository\CommentLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        return $this->render('home/profil.html.twig');
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueilList(ArticleRepository $rep)
    {

        $article = $rep->findAll();
        return $this->render('home/accueil.html.twig', [
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(ArticleRepository $rep, $id, Request $request, ObjectManager $manager)
    {
        $article = $rep->find($id);
        $user = $this->getUser();
        if ($user != null) {
            $username = $this->getUser()->getUsername();
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $comment->setUser($user);
            $comment->setAuthor($username);
            $comment->setCreatedAt(new \DateTime());
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('home/show.html.twig', [
            'articles' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * this for like 
     * 
     * @Route("/comment/{id}/like", name="like")
     * @param Comment $comment
     * @param CommentLikeRepository $rep
     * @param ObjectManager $manager
     * @return Response
     */
    public function like(Comment $comment, CommentLikeRepository $rep, ObjectManager $manager): Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'login so u can like this'
        ], 403);
        if ($comment->isLikedByUser($user)) {
            $like = $rep->findOneBy([
                'comment' => $comment,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'DISLIKE',
                'likes' => $rep->count(['comment' => $comment])
            ], 200);
        }

        $like = new CommentLike();
        $like->setComment($comment)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'LIKE',
            'likes' => $rep->count(['comment' => $comment])
        ], 200);
    }
}
