<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Form\PublicationType;
use BackendBundle\Entity\Publication;


class PublicationController extends Controller
{   
    private $session;
    
    public function __construct() {
        $this->session= new Session();
    }
    
    
    public function indexAction(Request $request){
        
       $em = $this->getDoctrine()->getManager();
       $user = $this->getUser();
       $publication = new Publication();
       $form = $this->createForm(PublicationType::class, $publication);
       
       $form->handleRequest($request);
       if ($form->isSubmitted()) {
            if ($form->isValid()) {
                //upload image
                $file = $form["image"]->getData();
                if(!empty($file) && $file != null){
                        $ext = $file->guessExtension();
                        if($ext =='jpg' || $ext =='jpeg' || $ext =='png' || $ext =='gif'){
                            $file_name= $user->getId(). time().'.'.$ext;
                            $file->move('upload/publication/images',$file_name);
                            
                            $publication->setImage($file_name);
                        }
                }else{
                        $publication->setImage(null);
                }
                //upload document
                $doc = $form["document"]->getData();
                if(!empty($doc) && $doc != null){
                        $ext = $doc->guessExtension();
                        if($ext =='pdf'){
                            $file_name= $user->getId(). time().'.'.$ext;
                            $doc->move('upload/publication/documents',$file_name);
                            
                            $publication->setDocument($file_name);
                        }
                }else{
                        $publication->setDocument(null);
                }
                
                $publication->setUser($user);
                $publication->setCreatedAt(new \DateTime("now"));
                
                $em->persist($publication);
                $flush = $em->flush();
                
                if ($flush == null) {
                    $status = "La publicacion se ha creado correctamente";
                } else {
                    $status = "Error al añadir la publicacion";
                }
            }else{
                $status = "La publicacion no se ah creado. pro que el formulario no es valido!!;";
            }
            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('home_publications');
       }
       
       return $this->render('AppBundle:Publication:home.html.twig',array(
          'form'=>$form->createView() 
       )); 
    }
}
