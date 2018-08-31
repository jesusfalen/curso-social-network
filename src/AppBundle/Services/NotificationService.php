<?php

namespace AppBundle\Services;
use BackendBundle\Entity\Notification;

class NotificationService {
    public $manager;
    
    public function __construct($manager) {
        $this->manager=$manager;
    }
    
    public function set($user,$type,$typeId,$extra = null) {
        $em = $this->manager;
        
        $notificaion = new Notification();
        $notificaion->setUser($user);
        $notificaion->setType($type);
        $notificaion->setTypeId($typeId);
        $notificaion->setReaded(0);
        $notificaion->setCreatedAt(new \DateTime("now"));
        $notificaion->setExtra($extra);
        
        $em->persist($notificaion);
        $flush = $em->flush();
        if($flush == null){
            $status = true;
        } else {
            $status = false;
        }
        
        return $status;
    }
    
    public function read($user) {
        $em = $this->manager;
        $notification_repo = $em->getRepository('BackendBundle:Notification');
        $notifications = $notification_repo->findBy(array('user'=>$user));
        
        foreach ($notifications as $notification){
            $notification->setReaded(1);
            $em->persist($notification);
        }
        
        $flush = $em->flush();
        if($flush == null){
            $status = true;
        } else {
            $status = false;
        }
        return $status;
    }
}
