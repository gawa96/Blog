<?php
namespace noteBundle\Controller;

use noteBundle\Entity\Note;
use noteBundle\Entity\categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ApiController extends Controller
{ 

//PARTIE note  
	/**
     * @Route("/api/note", name="APInote")
     * @Method("GET")
     */
    public function NoteAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('noteBundle:Note')->findAll();
        if (!$note) {
            return new Response("note pas trouvé");
        }
        $jsonContent = $serializer->serialize($note, 'json');
        return new Response($jsonContent);
    }

    /**
     * @Route("/api/note/del", name="APIdeletenote")
     * @Method("DELETE")
     */
    public function deleteNoteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $note = $em->getRepository('noteBundle:Note')->find($id);
        if (!$note) {
            return new Response("note pas trouvé");
        }
        try {
            $em->remove($note);
            $em->flush();
            return new Response("Transmission réussi");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }


     /**
     * @Route("/api/note/new", name="APInewNote")
     * @Method("POST")
     */
    public function newNoteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $json = $request->getContent();
        $data = json_decode($json, true);
        
        $titre = $data['titre'];
        $contenu = $data['contenu'];
        $date = new \DateTime($data['date']);
        $categorieId = $data['iDCategorie'];
        $note = new Note();
        $note->setTitre($titre);
        $note->setContenu($contenu);
        $note->setDate($date);
        $categorie = $em->getRepository('noteBundle:categorie')->find($categorieId);
        if (!$categorie) {
            return new Response("Aucune categorie trouvé");
        }
        $note->setIDCategorie($categorie);
        try {
            $em->persist($note);
            $em->flush();
            return new Response("Transmission réussi");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }

     /**
     * @Route("/api/note/edit", name="APIeditNote")
     * @Method("PUT")
     */
    public function editNoteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $json = $request->getContent();
        $data = json_decode($json, true);
        
        $id = $data['id'];
        $titre = $data['titre'];
        $contenu = $data['contenu'];
        $date = new \DateTime($data['date']);
        $categorieId = $data['iDCategorie'];
        $note = $em->getRepository('noteBundle:Note')->find($id);
        if (!$note) {
            return new Response("note pas trouvé");
        }
        $note->setTitre($titre);
        $note->setContenu($contenu);
        $note->setDate($date);
        $categorie = $em->getRepository('noteBundle:categorie')->find($categorieId);
        if (!$categorie) {
            return new Response("categorie pas trouvé");
        }
        $note->setIDCategorie($categorie);
        try {
            $em->flush();
            return new Response("Transmission réussi");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }

//partie Categorie


    /**
     * @Route("/api/categorie", name="APIcategories")
     * @Method("GET")
    */
    public function listCategoriesAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository('noteBundle:categorie')->findAll();
        if (!$categorie) {
            return new Response("categorie pas trouvé");
        }
        $jsonContent = $serializer->serialize($categorie, 'json');
        return new Response($jsonContent);
    }


    /**
     * @Route("/api/categorie/new", name="APInewCategorie")
     * @Method("POST")
     */
    public function newCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $json = $request->getContent();
        $data = json_decode($json, true);
        
        $nom = $data['nom'];
        $categorie = new categorie();
        $categorie->setNom($nom);
        try {
            $em->persist($categorie);
            $em->flush();
            return new Response("Transmission réussi");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }

     /**
     * @Route("/api/categorie/edit", name="APIeditCategorie")
     * @Method("PUT")
     */
    public function editCategorieAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $json = $request->getContent();
        $data = json_decode($json, true);
        
        $id = $data['id'];
        $nom = $data['nom'];
        $categorie = $em->getRepository('noteBundle:categorie')->find($id);
        if (!$categorie) {
            return new Response("categorie pas trouvé");
        }
        $categorie->setNom($nom);
        try {
            $em->flush();
            return new Response("Transmission réussi");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }

    /**
     * @Route("/api/categorie/del", name="APIdeleteCategorie")
     * @Method("DELETE")
     */
    public function delCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $categorie = $em->getRepository('noteBundle:categorie')->find($id);
        if (!$categorie) {
            return new Response("categorie pas trouvé");
        }
        try {
            $em->remove($categorie);
            $em->flush();
            return new Response("suppersssion réussite");
        } catch(Exception $e) {
            return new Response("erreur");
        }
    }
 }
