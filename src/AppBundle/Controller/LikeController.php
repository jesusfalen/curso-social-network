<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Publication;
use BackendBundle\Entity\Like;



class LikeController extends Controller
{   

    public function likeAction($id = null){
        
       $user = $this->getUser(); 
       $em = $this->getDoctrine()->getManager();
       
       $publications_repo = $em->getRepository('BackendBundle:Publication');
       $publication = $publications_repo->find($id);
       
       $like= new Like();
       $like->setUser($user);
       $like->setPublication($publication);
       
       $em->persist($like);
       $flush = $em->flush();
       if($flush == null){
           $status = 'Te gusta esta publicacion !!!';
       }else{
           $status = 'No se ha podido guardar el me gusta';
       }
       echo $status;
       exit;
    }
    
    public function unlikeAction($id = null){
        
       $user = $this->getUser(); 
       $em = $this->getDoctrine()->getManager();
       
       $like_repo = $em->getRepository('BackendBundle:Like');
       $like = $like_repo->findOneBy(array(
            'user'=>$user,
            'publication'=>$id
        ));
       
       $em->remove($like);
       
       $flush = $em->flush();
       if($flush == null){
           $status = 'Ya no te gusta esta publicacion !!!';
       }else{
           $status = 'No se ha podido desmarcar el me gusta';
       }
       echo $status;
       exit;
    }
    
}
