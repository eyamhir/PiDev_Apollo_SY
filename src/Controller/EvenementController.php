<?php
namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    /**
     * @Route("/Evenement", name="Evenement")
     */
    public function dashboard(): Response
    {
        $data = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        // Logic for handling events, for example, fetching data from the database

        return $this->render('Evenement/Eventhome.html.twig', [
            'data' => $data // Passing the fetched data to the Twig template with the variable name 'data'
        ]);
    }

    /**
     * @Route("/Evenementrole", name="Evenementrole")
     */
    public function dashboardrole(): Response
    {
        $data = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        // Logic for handling events, for example, fetching data from the database

        return $this->render('Evenement/Eventhomerole.html.twig', [
            'data' => $data // Passing the fetched data to the Twig template with the variable name 'data'
        ]);
    }

    /**
     * @Route("/Create", name="Create")
     */
    public function Create(Request $request)
    { 
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $this->addFlash('notice', 'Submitted Successfully!!'); 
            
            return $this->redirectToRoute('Evenement'); 
        }

    
        return $this->render('Evenement/Create.html.twig', [
            'form' => $form->createView() // Corrected variable name from $Form to $form
        ]);
    }
  
    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $this->addFlash('notice', 'Submitted Successfully!!');

            return $this->redirectToRoute('Evenement');
        }

        return $this->render('Evenement/updateEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice', 'Evenement supprimer!!');

        return $this->redirectToRoute('Evenement');
    }
}
