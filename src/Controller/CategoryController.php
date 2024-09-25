<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserPermissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/index', name: 'category_book')]
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('App\Entity\Category')->findAll();

        return $this->render('category/index.html.twig', [
            'data' => $categories,
            'title' => 'E-lybrary | Categories',
        ]);
    }

    #[Route('/add', name: 'category_add')]
    public function addAction(Request $request, UserPermissions $userPermissions)
    {
        if (!$userPermissions->isAdmin() && !$userPermissions->isLibrarian()) {

            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $bookCategory = new Category();
        $form = $this->createForm(CategoryType::class, $bookCategory, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bookCategory);
            $em->flush();

            return $this->redirectToRoute('category_book');
        }

        return $this->render('category/categoryForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Add Category',
        ]);
    }

    #[Route('/edit/{id}', name: 'category_edit')]
    public function editAction(Request $request, Category $id = null)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("App\Entity\Category")->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse("notValid");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
        }

        return $this->render('category/categoryForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Edit Category',
            'user' => $category,
        ]);
    }

    #[Route('/delete/{id}', name: 'category_delete')]
    public function deleteAction(Request $request, Category $id, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isSuperAdmin()) {

            return $this->redirectToRoute('login');
        }
        $this->getDoctrine()->getRepository(Category::class)->findBy(['deletedAt' => null]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('category');
    }
}
