<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function logout(): RedirectResponse
    {
        return new RedirectResponse('/');
    }
}
