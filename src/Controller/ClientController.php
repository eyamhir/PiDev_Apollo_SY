<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client/dashboard", name="client_dashboard")
     */
    public function dashboard(): Response
    {
        // Logique de gestion du tableau de bord du client
        // Par exemple, récupérer les données nécessaires à afficher dans le tableau de bord

        return $this->render('client/dashboard.html.twig', [
            // Passer des données à votre template si nécessaire
        ]);
    }
    
}