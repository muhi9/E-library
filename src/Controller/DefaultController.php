<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CheckingBooks;

class DefaultController extends AbstractController
{

    #[Route('/dashboard', name: 'index')]
    public function index(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/homepage', name: 'homepage')]
    public function homepage(Request $request, CategoryRepository $categoryRepository,BookRepository $bookRepository ): Response
    {
        $em = $this->getDoctrine()->getManager();
        $filterCategories = $categoryRepository->findAll();
       
        $filter = $request->query->all();
        if ($filter) {
        $b = $em->getRepository('App\Entity\Book')->findByFilters($filter);
            return $this->render('homepage\homepage.html.twig', ['data' => $b, 'categories' => $filterCategories]);
        }

        $book = $bookRepository->isValidated();

        return $this->render('homepage\homepage.html.twig', [
            'data' => $book, 
            'categories' => $filterCategories,
        ]);
    }
}
