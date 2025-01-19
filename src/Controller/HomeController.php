<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        // Symfony's default login controller does the work here, so you can leave this empty
        return $this->render('security/login.html.twig');
    }
}
