<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\BrowserKit\Response;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Following;


class FollowingController extends Controller
{
    private $session;
    
    public function __construct() {
        $this->session= new Session();
    }
    
    public function followAction(Request $request) {
        $user = $this->getUser();
        $followed_id = $request->get('followed');
        
        $em = $this->getDoctrine()->getManager();
        
        $user_repo = $em->getRepository('BackendBundle:User');
        $followed = $user_repo->find($followed_id);
        
        $following = new Following();
        $following->setUser($user);
        $following->setFollowed($followed);
        
        $em->persist($following);
        $flush = $em->flush();
        
        if($flush == null){
            
            $notification = $this->get('app.notification_service');
            $notification->set($followed,'follow',$followed->getId());
           
            $status= "Ahora estas siguiendo a este usuario!!";
        }else{
            $status= "No se ha podido seguir a este usuario!!";
        }
        
        echo $status;
        exit;
    }
    
    public function unfollowAction(Request $request) {
        $user = $this->getUser();
        $followed_id = $request->get('followed');
        
        $em = $this->getDoctrine()->getManager();
        
        $following_repo = $em->getRepository('BackendBundle:Following');
        $followed = $following_repo->findOneBy(array('user'=>$user,'followed'=>$followed_id));
        
        $em->remove($followed);
        $flush = $em->flush();
        
        if($flush == null){
            $status= "Has dejado de seguir correctamente a este usuario";
        }else{
            $status= "No se ha podido dejar de seguir";
        }
        
        echo $status;
        exit;
    }
    
    public function followingAction(Request $request,$nickname=null){
        $em = $this->getDoctrine()->getManager();
        
        if($nickname!=null){
            $user_repo = $em->getRepository("BackendBundle:User");
            $user = $user_repo->findOneBy(array('nick'=>$nickname));
        }else{
            $user = $this->getUser();
        }
        
        if(empty($user) || !is_object($user)){
            return $this->redirect($this->generateUrl('home_publications'));
        }
        
        $user_id = $user->getId();
        $dql = "SELECT f FROM BackendBundle:Following f WHERE f.user=$user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $following = $paginator->paginate(
                     $query,$request->query->getInt('page',1),5);
        
        return $this->render('AppBundle:Following:following.html.twig',array(
            'profile_user'=>$user,
            'type'=>'following',
            'pagination'=>$following
        ));
    }
    
    public function FollowedAction(Request $request,$nickname=null){
        $em = $this->getDoctrine()->getManager();
       
        if($nickname!=null){
            $user_repo = $em->getRepository("BackendBundle:User");
            $user = $user_repo->findOneBy(array('nick'=>$nickname));
        }else{
            $user = $this->getUser();
        }
        
        if(empty($user) || !is_object($user)){
            return $this->redirect($this->generateUrl('home_publications'));
        }

        $user_id = $user->getId();
        $dql = "SELECT f FROM BackendBundle:Following f WHERE f.followed=$user_id ORDER BY f.id DESC";
        $query = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $followed = $paginator->paginate(
                     $query,$request->query->getInt('page',1),5);
             
        return $this->render('AppBundle:Following:following.html.twig',array(
            'profile_user'=>$user,
            'type'=>'followed',
            'pagination'=>$followed
        ));
    }
}
