<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
