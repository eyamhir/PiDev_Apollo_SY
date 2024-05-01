<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/ai', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('AI_Image/ai.html.twig');
    }
 //   #[Route('/zoom', name: 'ap_index')]
  /*  public function indexa(): Response
    {
        return $this->render('conversation/zoom.html.twig');
    }*/
}

?>