<?php
namespace App\Controller;
use App\Entity\OeuvreArt;
use App\Form\OeuvreArt1Type;
use App\Repository\OeuvreArtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/** 
 * @Route("/oeuvre")
 */
class OeuvreController extends AbstractController
{
    /**
     * @Route("/",name="liste_Oeuvre")
     */
    public function index(OeuvreArtRepository $oeuvreArtRepository): Response
    {
        return $this->render('oeuvre/index.html.twig', [
            'oeuvre_arts' => $oeuvreArtRepository->findAll(),
        ]);
    }
    /**
     * @Route("/img",name="liste_Oeuvre1")
     */
    public function index1(OeuvreArtRepository $oeuvreArtRepository): Response
    {
        return $this->render('front/oeuvre.html.twig', [
            'oeuvre_arts' => $oeuvreArtRepository->findAll(),
        ]);
    }


    /**
     * @Route("/ajouter",name="ajouter_Oeuvre")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
    $oeuvreArt = new OeuvreArt();
    $form = $this->createForm(OeuvreArt1Type::class, $oeuvreArt);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer le fichier téléchargé
        $imageFile = $form->get('image_oeuvre')->getData();

        // Vérifier si un fichier a été téléchargé
        if ($imageFile) {
            // Déplacer le fichier vers le répertoire où vous souhaitez stocker les images
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('images_directory'), 
                $newFilename
            );

            // Mettre à jour l'entité avec le nom du fichier
            $oeuvreArt->setImageOeuvre($newFilename);
        }
        $entityManager->persist($oeuvreArt);
        $entityManager->flush();
        return $this->redirectToRoute('liste_Oeuvre', [], Response::HTTP_SEE_OTHER);
    }
        return $this->renderForm('oeuvre/ajouter_Oeuvre.html.twig', [
            'oeuvre_art' => $oeuvreArt,
            'form' => $form,
        ]);
    }

    /** 
     * @Route("/{id}",name="detail_Oeuvre", methods={"GET"})
     */
    public function detail(OeuvreArt $oeuvreArt): Response
    {
        return $this->render('oeuvre/detail_Oeuvre.html.twig', [
            'oeuvre_art' => $oeuvreArt,
        ]);
    }
     /** 
     * @Route("/front/{id}",name="detail_OeuvreF", methods={"GET"})
     */
    public function detailfront(OeuvreArt $oeuvreArt): Response
    {
        return $this->render('front/detailOeuvrefront.html.twig', [
            'oeuvre_art' => $oeuvreArt,
        ]);
    }

    /**
     * @Route("/{id}/edit",name="modifier_Oeuvre")
    */
    public function modifier(Request $request, OeuvreArt $oeuvreArt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OeuvreArt1Type::class, $oeuvreArt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('liste_Oeuvre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('oeuvre/modifier_Oeuvre.html.twig', [
            'oeuvre_art' => $oeuvreArt,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}",name="supprimer_Oeuvre",methods={"POST"})
     */
    public function delete(Request $request, OeuvreArt $oeuvreArt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvreArt->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oeuvreArt);
            $entityManager->flush();
        }
        return $this->redirectToRoute('liste_Oeuvre', [], Response::HTTP_SEE_OTHER);
    }
    
   
}
