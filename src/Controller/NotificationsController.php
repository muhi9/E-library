<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Service\UserPermissions;
use App\Controller\NotificationsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    public function index(): Response
    {
        return $this->render('notifications/index.html.twig', [
            'controller_name' => 'NotificationsController',
        ]);
    }

    #[Route('/notificationCreate', name: 'create_notification')]
    public function create( UserPermissions $userPermissions,)
    {
        if (!$userPermissions->isAdmin() && !$userPermissions->isLibrarian() && !$userPermissions->isTeacher()) {

            return $this->redirectToRoute('index');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $notification = new Notifications;
        $form = $this->createForm(NotificationsType::class, $notification, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();
            return;
        }



        return $this->render('notifications/index.html.twig');
    }
}
