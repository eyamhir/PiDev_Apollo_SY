<?php

namespace App\Controller;

use App\Entity\Portfolio;
use App\Entity\User;
use App\Form\PortfolioType;
use App\Repository\OeuvreArtRepository;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/potfolio")
 */
class PotfolioController extends AbstractController
{
/**
 * @Route("/", name="liste_Portfolio")
 */
    // #[Route('/', name: 'liste_Portfolio', methods: ['GET'])]
public function liste_PO(PortfolioRepository $portfolioRepository): Response
{
    return $this->render('potfolio/index.html.twig', [
        'portfolios' => $portfolioRepository->findAll(),
    ]);
}
/**
 * @Route("/artiste", name="liste_Artiste")
 */
   
    public function liste_artiste(PortfolioRepository $portfolioRepository): Response
    {
        return $this->render('front/artiste.html.twig', [
            'portfolios' => $portfolioRepository->findAll(),
        ]);
    }
  /**
     * @Route("/ajouter", name="portfolio_ajouter", methods={"GET", "POST"})
     */
    public function ajouter(Request $request, Security $security): Response
    {

        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        $portfolio = new Portfolio();
        $portfolio->setIdUser($user);
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'image téléchargée depuis le formulaire
            $imageFile = $form->get('imageUrl')->getData();

            // Vérifier si une image a été téléchargée
            if ($imageFile) {
                // Générer un nom de fichier unique
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacer le fichier vers le répertoire où les images doivent être sauvegardées
            
                    $imageFile->move(
                        $this->getParameter('portfolio_images'),
                        $newFilename
                    );
                

                // Mettre à jour l'entité avec le nom de fichier
                $portfolio->setImageUrl($newFilename);
            }

            // Enregistrer l'entité dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($portfolio);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page
            return $this->redirectToRoute('liste_Portfolio');
        }

        return $this->render('potfolio/ajouterportfolio.html.twig', [
            'form' => $form->createView(),
        ]);
    }



/**
 * @Route("/{id}", name="detail",methods={"GET"})
 * @ParamConverter("portfolio",  class="App\Entity\Portfolio")
 */
    
    public function details(Portfolio $portfolio): Response
    {
        return $this->render('potfolio/details_portfolio.html.twig', [
            'portfolio' => $portfolio,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="modifier_Portfolio")
     * @ParamConverter("portfolio", class="App\Entity\Portfolio")

    */
    // #[Route('/{id}/edit', name: 'modifier_Portfolio', methods: ['GET', 'POST'])]
    public function modifier(Request $request, Portfolio $portfolio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PortfolioType::class, $portfolio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('liste_Portfolio', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('potfolio/modifier_Portfolio.html.twig', [
            'portfolio' => $portfolio,
            'form' => $form,
        ]);
    }
/**
 * @Route("/{id}", name="supprimer_Portfolio",methods={"POST"})
 * @ParamConverter("portfolio", class="App\Entity\Portfolio")

 */
    public function supprimer_Portfolio(Request $request, Portfolio $portfolio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$portfolio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($portfolio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liste_Portfolio', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/mon-portfolio/{userId}", name="mon_portfolio_par_user")
     */
    public function monportfolio(int $userId, PortfolioRepository $portfolioRepository): Response
    {
        // Récupérer le portfolio par l'ID de l'utilisateur
        $portfolio = $portfolioRepository->findByUserId($userId);

        // Vérifier si un portfolio a été trouvé
        if (!$portfolio) {
            // Si aucun portfolio n'a été trouvé, rediriger vers une autre page
            return $this->redirectToRoute('portfolio_ajouter');
        }

        // Afficher les détails du portfolio
        return $this->render('potfolio/details_portfolio.html.twig', [
            'portfolio' => $portfolio,
        ]);
    }
    public function getOeuvresUtilisateurConnecte(OeuvreArtRepository $oeuvreArtRepository, PortfolioRepository $portfolioRepository): Response
{
    // Obtenez l'utilisateur connecté
    $user = $this->getUser();

    if ($user) {
        // Récupérez le portfolio de l'utilisateur connecté en utilisant la méthode findByUserId
        $portfolio = $portfolioRepository->findByUserId($user->getUserIdentifier());

        // Vérifiez si un portfolio existe pour l'utilisateur connecté
        if ($portfolio) {
            // Récupérez les œuvres associées au portfolio de l'utilisateur connecté en utilisant la méthode findByPortfolioId
            $oeuvres = $oeuvreArtRepository->findByPortfolioId($portfolio->getId());

            // Faites quelque chose avec les œuvres récupérées
            // Par exemple, passez les œuvres à un template Twig pour les afficher
            return $this->render('votre_template.html.twig', [
                'oeuvres' => $oeuvres,
            ]);
        } else {
            // Aucun portfolio associé à l'utilisateur connecté
            // Rediriger vers la page de création de portfolio ou afficher un message d'erreur
            return new Response('Vous devez créer un portfolio pour accéder à cette fonctionnalité.');
        }
    } else {
        // L'utilisateur n'est pas connecté
        // Rediriger vers la page de connexion ou afficher un message d'erreur
        return new Response('Vous devez être connecté pour accéder à cette fonctionnalité.');
    }
}
    
}
