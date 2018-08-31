<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $user_id = $user->getId();
        $dql = "SELECT n FROM BackendBundle:Notification n WHERE n.user = $user_id ORDER BY n.id DESC";
        $query = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $notifications = $paginator->paginate(
                     $query,$request->query->getInt('page',1),5);
        
        
        $notification = $this->get('app.notification_service');
        $notification->read($user);
        
        return $this->render('AppBundle:Notification:notification_page.html.twig',array(
            'user'=>$user,
            'pagination'=>$notifications
        ));
    }
    
    public function CountNotificationsAction() {
        $em = $this->getDoctrine()->getManager();
        $notification_repo = $em->getRepository('BackendBundle:Notification');
        $notification = $notification_repo->findBy(array(
            'user'=>$this->getUser(),
            'readed'=>0
        ));
        
        echo count($notification);
        exit;
    }
}
