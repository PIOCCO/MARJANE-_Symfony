<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PremiumController extends AbstractController
{
    #[Route('/Premium.html', name: 'premium')]
    public function index(): Response
    {
        return $this->render('premium/index.html.twig');
    }
}
