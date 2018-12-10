<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Entity\Usager;
use App\Entity\Responsable;
use App\Entity\Habitation;

use App\Form\UsagerType;
use App\Form\UsagerModifierType;
use App\Form\UsagerArchiverType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class UsagerController extends AbstractController
{
    
	public function ajouterUsager(Request $request)
	{
		
		$usager = new Usager();
		$form = $this->createForm(UsagerType::class, $usager);
		
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$usager = $form->getData();
			$usager->setArchive(false);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($usager);
			$entityManager->flush();
	   
			return $this->render('usager/consulter.html.twig', ['usager' => $usager,]);
		}
		else{
			return $this->render('usager/ajouter.html.twig', array('form' => $form->createView(),));
		}
		
	}
	
	public function consulterUsager($id)
	{
		
		$usager = $this->getDoctrine()
        ->getRepository(Usager::class)
        ->find($id);

		if (!$usager) {
			throw $this->createNotFoundException(
            'Aucun usager trouvé avec le numéro '.$id
			);
		}

		//return new Response('Usager : '.$usager->getLogin());
		return $this->render('usager/consulter.html.twig', [
            'usager' => $usager,]);
	}
	
	public function listerUsager()
	{
		$repository = $this->getDoctrine()->getRepository(Usager::class);
		$usagers = $repository->findByArchive(false);
		return $this->render('usager/lister.html.twig', [
            'pUsagers' => $usagers,]);
	}
	
	public function modifierUsager($id, Request $request){

	//récupération du usager dont l'id est passé en paramètre
	$usager = $this->getDoctrine()
		->getRepository(Usager::class)
		->find($id);

		if (!$usager) {
			throw $this->createNotFoundException('Aucun usager trouvé avec le numéro '.$id);
		}
		else
		{
            $form = $this->createForm(UsagerModifierType::class, $usager);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                 $usager = $form->getData();
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($usager);
                 $entityManager->flush();
                 return $this->render('usager/consulter.html.twig', ['usager' => $usager,]);
           }
           else{
                return $this->render('usager/ajouter.html.twig', array('form' => $form->createView(),));
           }
        }
	}
	
	public function archiverUsager($id, Request $request){

	//récupération du usager dont l'id est passé en paramètre
	$usager = $this->getDoctrine()
		->getRepository(Usager::class)
		->find($id);

		if (!$usager) {
			throw $this->createNotFoundException('Aucun usager trouvé avec le numéro '.$id);
		}
		else
		{
            $form = $this->createForm(UsagerArchiverType::class, $usager);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                 $usager = $form->getData();
				 
				 $usager->setArchive(true);
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($usager);
                 $entityManager->flush();
				 $repository = $this->getDoctrine()->getRepository(Usager::class);
				$usagers = $repository->findAll();
				return $this->render('usager/lister.html.twig', ['pUsagers' => $usagers,]);
           }
           else{
                return $this->render('usager/ajouter.html.twig', array('form' => $form->createView(),));
           }
        }
	}
}
