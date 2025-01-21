<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(Request $request, Security $security)
    {
        // If the user is already authenticated, redirect them to another page (e.g., homepage).
        if ($security->getUser()) {
            return $this->redirectToRoute('home'); // Adjust to your homepage route
        }

        return $this->render('security/login.html.twig');
    }
}
