<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\UserBooks;
use App\Service\UserPermissions;
use App\Form\UserType;
use App\Repository\UserRepository;
use \Controller\FormValidationControllerTrait;

use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/user')]
class UserController extends AbstractController
{

    // use \FBaseBundle\Controller\DataGridControllerTrait;
    // use FormValidationControllerTrait;
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[Route('/', name: 'user')]
    public function indexAction(Request $request, UserPermissions $userPermission, UserRepository $userRepository)
    {
        if (!$userPermission->isAdmin()) {

            return $this->redirectToRoute('login');
        }

        return $this->render('user/index.html.twig');
    }


    #[Route('/edit/{id}', name: 'user_edit')]
    public function editAction(Request $request, User $id, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isSuperAdmin() && $id != $this->getUser()) {

            return $this->redirectToRoute('user_edit', ['id' => $this->getUser()->getId()]);
        }

        $this->doctrine;
        $doctrine = $this->get('doctrine');
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("App\Entity\User")->find($id);
        if (empty($user)) {
            return $this->redirectToRoute('user');
        }

        $form = $this->createForm(UserType::class, $user, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse("notValid");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user');
        }

        return $this->render('user\userForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Profile | Lybrary',
            'user' => $user,
        ]);
    }

    #[Route('/list', name: 'user_list')]
    public function listAction(Request $request, UserPermissions $userPermission, UserRepository $userRepository)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isSuperAdmin()) {

            return $this->redirectToRoute('login');
        }

        $userName = $request->query->get('userName') ?? NULL;
        $users = $userRepository->searchUser($userName);

        return $this->render('user\list.html.twig', ['data' => $users, 'filter' => $userName]);
    }

    /**
     * @Route("/delete/{id}", name = "user_delete")
     */
    public function deleteAction(Request $request, User $id, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isSuperAdmin()) {

            return $this->redirectToRoute('login');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('user');
    }

    #[Route('/books', name: 'user_books')]
    public function index(): Response
    {
        $userBooks = $this->getUser()->getUserBooks()->getValues();
        $booksInfo = [];
        foreach ($userBooks as $b) {
            $booksInfo[] = [
                "bookId" => $b->getOrderedBook()->getValues()[0]->getId(),
                "title" => $b->getOrderedBook()->getValues()[0]->getTitle(),
                "author" => $b->getOrderedBook()->getValues()[0]->getAuthorList(),
                "price" => $b->getPrice(),
                "cover" => $b->getOrderedBook()->getValues()[0]->getCover(),
                "bookFile" => $b->getOrderedBook()->getValues()[0]->getBook(),
                "date" => $b->getDate()
            ];
        }

        return $this->render('user\userBooks.html.twig', [
            'data' => $booksInfo,
        ]);
    }

    #[Route('/order', name: 'user_order')]
    public function orderAction(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cart =  $user->getCart()->getValues();

        $bookInfo = [];
        foreach ($cart as $elm) {
            $bookInfo[] = [
                "bookId" => $elm->getCartBook()->getId(),
                "bookPrice" => $elm->getCartBook()->getPrice()
            ];
            $em->remove($elm);
        }

        foreach ($bookInfo as $key) {
            $orderedBook = new UserBooks;
            $book = $em->getRepository("App\Entity\Book")->find($key['bookId']);
            $orderedBook->setUser($user);
            $orderedBook->addOrderedBook($book);
            $orderedBook->setPrice($key['bookPrice']);
            $orderedBook->setDate(date("d.m.Y"));
            $em->persist($orderedBook);
            $em->flush();
        }

        return $this->redirectToRoute('user_books');
    }
}
