<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/footer')]
class FooterController extends AbstractController
{

    #[Route('/about', name: 'footer_aboute')]
    public function about(): Response
    {
        return $this->render('footer/about.html.twig');
    }

    #[Route('/terms', name: 'footer_terms')]
    public function terms(): Response
    {
        return $this->render('footer/terms.html.twig');
    }

    #[Route('/privaci', name: 'footer_privaci')]
    public function privaci(): Response
    {
        return $this->render('footer/privaciPolicy.html.twig');
    }
    
    #[Route('/services', name: 'footer_services')]
    public function services(): Response
    {
        return $this->render('footer/services.html.twig');
    }

    #[Route('/contact', name: 'footer_contact')]
    public function contact(): Response
    {
        return $this->render('footer/contact.html.twig');
    }
}
