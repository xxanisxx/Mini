<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CrudController extends AbstractController
{
    /**
     * @Route("create", name="create")
     */
    public function create(Request $request, ObjectManager $manager){
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            $article->setCreatedAt(new \DateTime());
            $manager->persist($article);
            $manager->flush();

            /*return $this->redirectToRoute('show',['id' => $article->getId()
            ]);*/
            return $this->redirectToRoute('profil');
        }
        
        return $this->render('home/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ArticleRepository $rep, Request $req, ObjectManager $manager, $id){
        $article = $rep->find($id);
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('profil');
    }

}
