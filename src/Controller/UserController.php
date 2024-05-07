<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
 /**
     * @Route("/", name="display_admin")
     */
    // #[Route('/', name: 'display_admin', methods: ['GET'])]
    public function indexAdmin(): Response
    {
        return $this->render('Admin/index.html.twig');
    }
 /**
     * @Route("/utilisateur", name="app_utilisateur_index")
     */
    // #[Route('/utilisateur', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UserRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
 /**
     * @Route("/utilisateur/new", name="app_utilisateur_new")
     */
    // #[Route('/utilisateur/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new User();
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
 /**
     * @Route("/utilisateur/{id}", name="app_utilisateur_show")
     */
    // #[Route('/utilisateur/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(User $utilisateur): Response
    {
        return $this->render('utilisateur/showtest.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
 /**
     * @Route("/utilisateur/{id}/edit", name="app_utilisateur_edit")
     */
    // #[Route('/utilisateur/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
 /**
     * @Route("/utilisateur/{id}", name="app_utilisateur_delete",methods={"POST"})
     */
    // #[Route('/utilisateur/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, User $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
 /**
     * @Route("/client", name="display_client" ,methods={"GET"})
     */
    // #[Route('/client', name: 'display_client', methods: ['GET'])]
    public function indexClient(): Response
    {
        return $this->render('client/index.html.twig');
    }
}
