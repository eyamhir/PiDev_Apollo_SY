<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    /**
     * @Route("/artiste/dashboard", name="artiste_dashboard")
     */
    public function dashboard(): Response
    {
        // Logique de gestion du tableau de bord de l'artiste
        // Par exemple, récupérer les données nécessaires à afficher dans le tableau de bord

        return $this->render('artiste/dashboard.html.twig', [
            // Passer des données à votre template si nécessaire
        ]);
    }
}
