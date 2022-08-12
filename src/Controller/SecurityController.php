<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/404', name: '404')]
    public function pageNotFound()
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }

    #[Route('/403', name: '403')]
    public function accessDenied()
    {
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }

    #[Route('/error', name: 'error_page')]
    public function errorPage()
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig');
    }
}
