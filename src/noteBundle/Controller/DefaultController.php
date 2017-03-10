<?php

namespace noteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use noteBundle\Entity\Note;
use noteBundle\Entity\categorie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DefaultController extends Controller
{

    /**
     * @Route("/", name ="note")
     */
    public function noteAction()
    {
    	$em =$this->getDoctrine()->getManager();
    	$note = $em->getRepository('noteBundle:Note')->findAll();
    	if (!$note){
            return $this->render('noteBundle:Notes:Nonote.html.twig');
    		
    	}
       
       return $this->render('noteBundle:Notes:index.html.twig',array('note'=>$note));
    }
 	 /**
     * @Route("/Notes/AddNote", name="AddNote")
     */

      public function addNoteAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $note = new Note();
        $form = $this->createFormBuilder($note)
            ->add('titre', TextType::class, array('attr' => array('placeholder' => 'Titre')))
            
            ->add('Contenu', TextareaType::class, array('attr' => array('placeholder' => 'Contenu')))
            
            ->add('date', DateType::class)
            
            ->add('iDCategorie', EntityType::class, array('class' => 'noteBundle:categorie','choice_label' => 'nom','label' => 'Catégorie'))
            
            ->add('enregistrer', SubmitType::class)
            
            ->getForm();

        $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        $note = $form->getData();
	        $em->persist($note);
	        $em->flush();
	        $request->getSession()->getFlashBag()->add('notice', 'Success');
            $this->addFlash(
            'notice',
            'Your new note has been saved!'
            );
      		return $this->redirectToRoute('note');
	    }

        return $this->render('noteBundle:Notes:AddNote.html.twig', array(
            'form' => $form->createView()));
    }

    /**
    *@Route("/Categories/AddCategorie", name ="AddCategorie")
    */
    	public function addCategorieAction (request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $categorie = new categorie();
        $form = $this->createFormBuilder($categorie)
            ->add('Nom', TextType::class, array('attr' => array('placeholder' => 'nom')))
            ->add('enregistrer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        	    if ($form->isSubmitted() && $form->isValid()) {
	        $em->persist($categorie);
	        $em->flush();
	        $request->getSession()->getFlashBag()->add('notice', 'Success');
            $this->addFlash(
            'notice',
            'Your new note has been saved!'
            );
      		return $this->redirectToRoute('note');
	    }

        return $this->render('noteBundle:Notes:AddNote.html.twig', array(
            'form' => $form->createView()));
    }

    /**
    *@Route("/Option/editNote/{id}", name="editNote", requirements={"id": "\d+"})
    */
    public function editNoteAction (request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('noteBundle:Note')->find($id);
        if (!$note) {
            throw $this->createNotFoundException('Note not found');
        }

        $form = $this->createFormBuilder($note)
            ->add('titre', TextType::class, array('attr' => array('placeholder' => 'Titre')))
            
            ->add('Contenu', TextareaType::class, array('attr' => array('placeholder' => 'Contenu')))
            
            ->add('date', DateType::class)
            
            ->add('iDCategorie', EntityType::class, array('class' => 'noteBundle:categorie','choice_label' => 'nom','label' => 'Catégorie'))
            
            ->add('enregistrer', SubmitType::class)
            
            ->getForm();

        $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        $note = $form->getData();
	        $em->persist($note);
	        $em->flush();
	        $request->getSession()->getFlashBag()->add('notice', 'Success');
            $this->addFlash(
            'notice',
            'Votre note est sauvé'
            );
      		return $this->redirectToRoute('note');
	    }

        return $this->render('noteBundle:Notes:AddNote.html.twig', array(
            'form' => $form->createView()));
    }

      /**
     * @Route("/supNote/{id}", name="supNote", requirements={"id": "\d+"})
     */
    public function supNoteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('noteBundle:Note')->find($id);
        if (!$note) {
            throw $this->createNotFoundException('Note not found');
        }
        $em->remove($note);
        $em->flush();
        return $this->redirectToRoute('note');
    }
    /**
    *@Route ("/Categories/ListCategorie", name="ListeCategorie")
    */
    public function listeCategorieAction(request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $cate = $em->getRepository('noteBundle:categorie')->findAll();
        if (!$cate) {
            throw $this->createNotFoundException('Aucune Categorie trouvée');
        }

  		return $this->render('noteBundle:Categories:ListeCategorie.html.twig',array('cate'=>$cate));
    }

    /**
    *@Route("EditCate/{id}", name="EditCate", requirements={"id": "\d+"})
    */
    public function EditCateAction (request $request , $id)
    {	
    	$em = $this->getDoctrine()->getManager();
    	$categorie = $em->getRepository('noteBundle:categorie')->find($id);
    	if (!$categorie) {
          throw $this->createNotFoundException('Note not found');
        }

        $form = $this->createFormBuilder($categorie)
            ->add('Nom', TextType::class, array('attr' => array('placeholder' => 'nom')))
            ->add('enregistrer', SubmitType::class)
            ->getForm();


        $form->handleRequest($request);
        	    if ($form->isSubmitted() && $form->isValid()) {
	        $em->persist($categorie);
	        $em->flush();
	        $request->getSession()->getFlashBag()->add('notice', 'Success');
            $this->addFlash(
            'notice',
            'Your new note has been saved!'
            );
      		return $this->redirectToRoute('ListeCategorie');
	    }

        return $this->render('noteBundle:Categories:AddCategorie.html.twig', array(
            'form' => $form->createView()));
    }

    /**
    *@Route("SuppCate/{id}", name="SuppCate", requirements={"id": "\d+"})
    */
    public function SuppCateAction (request $request , $id)
    {	
    	$em = $this->getDoctrine()->getManager();
    	$categorie = $em->getRepository('noteBundle:categorie')->find($id);
    	if (!$categorie) {
          throw $this->createNotFoundException('Note not found');
        }
        try 
        {
        	$em->remove($categorie);
        	$em->flush();
        	return $this->redirectToRoute('ListeCategorie');
        }catch(\Doctrine\DBAL\DBALException $e)
        {
        	$this->addflash(
				'notice',
				'la catégorie est utilisé'
				);
				return $this->redirectToRoute('ListeCategorie');
        }
    }
}
