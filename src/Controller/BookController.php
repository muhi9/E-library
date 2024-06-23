<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Security\EmailVerifier;
use App\Service\FileUploader;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Form\BookType;
use App\Form\AuthorType;
use App\Service\UserPermissions;

use App\Service\Mailer;

#[Route('/book')]
class BookController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/index', name: 'book')]
    public function index(Request $request, Mailer $mailer): Response
    {

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'title' => 'Lybrary | Book',
        ]);
    }

    #[Route('/list', name: 'book_list')]
    public function listAction(Request $request,  BookRepository $bookRepository, UserPermissions $userPermissions)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('App\Entity\Book')->isValidated();

        $bookTitle = $request->query->get('bookTitle');
        if ($bookTitle) {
            $b = $em->getRepository('App\Entity\Book')->findByTitle($bookTitle);
            return $this->render('book\list.html.twig', ['data' => $b, 'filter' => $bookTitle]);
        }

        if ($userPermissions->isAdmin() || $userPermissions->isLibrarian()) {
            $book = $em->getRepository('App\Entity\Book')->findAll();
        }

        return $this->render('book\list.html.twig', ['data' => $book]);
    }

    #[Route('/add', name: 'book_add')]
    public function addAction(Request $request, FileUploader $fileUploader, UserPermissions $userPermissions, MailerInterface $mailer)
    {
        if (!$userPermissions->isAdmin() && !$userPermissions->isLibrarian() && !$userPermissions->isTeacher()) {

            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $book = new Book();

        $form = $this->createForm(BookType::class, $book, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookFile = $form->get('book')->getData();
            $bookCover = $form->get('cover')->getData();

            if ($bookFile || $bookCover) {
                $bookFileName = $fileUploader->upload($bookFile);
                $book->setBook($bookFileName);

                $bookCoverName = $fileUploader->upload($bookCover);
                $book->setCover($bookCoverName);

                $description = $book->getDescription();
                $description = substr($description, 3, -4);
                $book->setDescription($description);

                $em->persist($book);
                $em->flush();
            }

            // Send email to admin/lybrarian for new book
            $email = (new Email())
                ->from('muhi-tu@abv.bg')
                ->to("muhi-tu@abv.bg")  // email from admin or lybrarian
                ->subject('Please confirm the new book')
                ->text("Check and confirm the following book -> id:{$book->getId()}, title:{$book->getTitle()}, author:{$book->getAuthor()}
                link: http://127.0.0.1/book/info/{$book->getId()}");

            $mailer->send($email);
            if ($userPermissions->isTeacher()) {
                $this->addFlash('success', 'Your book has been successfully uploaded, please wait for confirmation.');
                return $this->redirectToRoute('index');
            }

            $this->addFlash('success', 'Your book has been successfully uploaded.');
            return $this->redirectToRoute('index');
        }

        return $this->render('bookForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Add Book',
            // 'action_name' => 'Add new book',
        ]);
    }

    #[Route('/edit/{id}', name: 'book_edit')]
    public function editAction(Request $request, Book $id = null)
    {

        $em = $this->getDoctrine()->getManager();

        $book = $em->getRepository("App\Entity\Book")->find($id);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse("notValid");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $book->getDescription();
            $description = substr($description, 3, -4);
            $book->setDescription($description);

            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book');
        }

        return $this->render('\bookForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit Book | Lybrary',
            'user' => $book,
        ]);
    }

    #[Route('/validate/{id}', name: 'book_validate')]
    public function validateAction(Request $request, Book $id = null, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isLibrarian()) {

            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository("App\Entity\Book")->find($id);
        $book->setValidation('true');
        $book->setIsPublish('true');
        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('book');
    }

    #[Route('/undo/{id}', name: 'book_undo')]
    public function undoAction(Request $request, Book $id = null, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isLibrarian()) {

            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository("App\Entity\Book")->find($id);
        $book->setValidation(false);
        $book->setIsPublish(false);
        $em->persist($book);
        $em->flush();

        return $this->redirectToRoute('book');
    }

    #[Route('/delete/{id}', name: 'book_delete')]
    public function deleteAction(Request $request, Book $id, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isLibrarian()) {

            return $this->redirectToRoute('login');
        }
        $this->getDoctrine()->getRepository(Book::class)->findBy(['deletedAt' => null]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('book');
    }

    #[Route('/info/{id}', name: 'book_info')]
    public function infoAction(Request $request, Book $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('App\Entity\Book')->find($id);
        // $category = $book->getCategories()->getValues()[0]->getName();
        $category = $book->getCategoriList();
        $book->setViews($book->getViews() + 1);
        $em->persist($book);
        $em->flush();
        return $this->render('book\info.html.twig', [
            'data' => $book,
            'category' => $category
        ]);
    }

    #[Route('/authors', name: 'authors_book')]
    public function authorsAction(Request $request, AuthorRepository $authorRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $authorName = $request->query->get('authorName');

        if (!empty($authorName)) {

            $author = $em->getRepository("App\Entity\Author")->searchAuthor($authorName);
            return $this->render('book\authors.html.twig', ['data' => $author, 'filter' => $authorName]);
        }

        $authors = $em->getRepository('App\Entity\Author')->findAll();

        return $this->render('book\authors.html.twig', ['data' => $authors]);
    }

    #[Route('/addAuthor', name: 'addAuthor_book')]
    public function addAuthor(Request $request, UserPermissions $userPermissions)
    {
        if (!$userPermissions->isAdmin() && !$userPermissions->isLibrarian()) {

            return $this->redirectToRoute('index');
        }

        $em = $this->getDoctrine()->getManager();
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('authors_book');
        }

        return $this->render('book/authorForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Add Author',
        ]);
    }

    #[Route('/editAuthor/{id}', name: 'author_edit')]
    public function authorEdit(Request $request, Author $id = null)
    {

        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository("App\Entity\Author")->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if (isset($notValid['formErrors']) || $request->get('validationRequest') == 'only') {
            return new JsonResponse("notValid");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('authors_book');
        }

        return $this->render('book/authorForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Edit Author',
            'user' => $author,
        ]);
    }

    #[Route('/deleteAuthor/{id}', name: 'author_delete')]
    public function deleteAuthor(Request $request, Author $id, UserPermissions $userPermission)
    {
        if (!$userPermission->isAdmin() && !$userPermission->isSuperAdmin()) {

            return $this->redirectToRoute('index');
        }
        $this->getDoctrine()->getRepository(Author::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('authors_book');
    }

    #[Route('/authorsBooks/{authorName}', name: 'authors_books')]
    public function authorsBookS(Request $request, BookRepository $bookRepository, $authorName){
        $books = $bookRepository->getAuthorsBooks($authorName);
     
        return $this->render('book\authorsBooks.html.twig',['data' => $books]);
    }
}
