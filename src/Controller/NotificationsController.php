<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Entity\User;
use App\Form\NotificationsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserPermissions;
use Symfony\Component\Routing\Annotation\Route;

class NotificationsController extends AbstractController
{

    #[Route('/notifications', name: 'app_notifications')]
    public function index(Request $request,UserPermissions $userPermissions): Response
    {
        if ($userPermissions->isAdmin() && $userPermissions->isLibrarian()  && $userPermissions->isTeacher()) {

            return $this->redirectToRoute('app_notifications_create');
        }

        //$notifications = $this->getUser()->getNotifications()->getValues();
        $em = $this->getDoctrine()->getManager();
        $notifications = $em->getRepository(Notifications::class)->findBy(['user'=>$this->getUser(), 'status'=>false], ['id'=>'DESC'], 3);
        
        $data = array();
        foreach($notifications as $elm){
           $data[] = [
            'id'=>$elm->getId(),
                'subject'=>$elm->getSubject(),
                'description'=>$elm->getDescription(),
                'status'=>$elm->isStatus(),
           ];
            // array_push( $data,[
            //     'id'=>$elm->getId(),
            //     'subject'=>$elm->getSubject(),
            //     'description'=>$elm->getDescription(),
            //     'status'=>$elm->isStatus(),
            // ]);
        }
        
        // Handle the AJAX request
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($data);
            //echo json_encode(array_slice($data, 0, 3));
        }

        // If not an AJAX request, return a 404 error
        return new Response('Not Found', 404);
        return $this->render('notifications/index.html.twig');
    }


    // #[Route('/notifications', name: 'app_notifications')]
    // public function index(): Response
    // {       

    //     $notifications = $this->getUser()->getNotifications()->getValues();

    //     return $this->render('notifications/index.html.twig', [
    //         'notifications' => $notifications
    //     ]);
    // }

    #[Route('/notifications_create', name: 'app_notifications_create')]
    public function addAction(Request $request, UserPermissions $userPermissions)
    {
        if (!$userPermissions->isAdmin() && !$userPermissions->isLibrarian()  && !$userPermissions->isTeacher()) {

            return $this->redirectToRoute('app_notifications');
        }

        $em = $this->getDoctrine()->getManager();
        $notifications = new Notifications();
        $form = $this->createForm(NotificationsType::class, $notifications);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $rolesStudent = $this->getDoctrine()->getRepository(User::class)->findBy(['roles' => array('["ROLE_STUDENT"]')]);
            $notifications->setCreator($user);

            foreach ($rolesStudent as $role) {

                $notifications->setUser($role);
                $em->persist($notifications);
                $notifications = clone $notifications;
            }
            $em->flush();


            return $this->redirectToRoute('index');
        }

        return $this->render('notifications/notificationsForm.html.twig', [
            'form' => $form->createView(),
            'title' => 'E-lybrary | Add Notifications',
        ]);
    }
}
