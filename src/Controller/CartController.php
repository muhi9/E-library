<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Cart;
use App\Entity\User;
use Container2lOnPYV\getContainer_GetenvService;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'cart_add')]
    public function addAction(Request $request, Book $id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('App\Entity\Book')->find($id);
        $user = $this->getUser();

        //check the new book is have in cart
        $userCart =  $user->getCart()->getValues();
        $booksInCart = [];
        foreach ($userCart as $cart) {
            $booksInCart[] = $cart->getCartBook()->getId();
        }

        if (in_array($id->getId(), $booksInCart)) {
            $this->addFlash('success', 'The book has been added to the cart');
            return $this->redirectToRoute('cart');

        }

            $cart = new Cart;

            $cart->setCartUser($user);
            $cart->setCartBook($book);

            $em->persist($cart);
            $em->flush();

            return $this->redirectToRoute('cart');
        }

    #[Route('/list', name: 'cart_list')]
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cart =  $user->getCart()->getValues();
        $cartInfo = [];
        foreach ($cart as $elm) {
            $cartInfo[] = [
                'id' => $elm->getId(),
                'title' => $elm->getCartBook()->getTitle(),
                'author' => $elm->getCartBook()->getAuthorList(),
                'cover' => $elm->getCartBook()->getCover(),
                'price' => $elm->getCartBook()->getPrice(),
                'book' => $elm->getCartBook()->getBook(),

            ];
        }

        return $this->render('cart/index.html.twig', ['data' => $cartInfo]);
    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function deleteAction(Request $request, Cart $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return $this->redirectToRoute('cart_list');
    }
}
