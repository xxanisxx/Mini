<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;

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
}
