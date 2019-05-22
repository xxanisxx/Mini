<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_view")
     */
    public function index(UserRepository $userRep, CategoryRepository $categoryRep)
    {
        $user = $userRep->findAll();
        $category = $categoryRep->findAll();
        return $this->render('admin/index.html.twig', [
            'user' => $user,
            'category' => $category,
        ]);
    }

    /**
     * @Route("/createCategory", name="create_category")
     */
    public function createUser(Request $request, ObjectManager $manager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('admin_view');
        }

        return $this->render('admin/createCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
