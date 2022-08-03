<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/404', name: '404')]
    public function pageNotFound(): Response
    {
        return $this->render('security/404.html.twig');
    }
}
