<?php

namespace App\Controller;

use App\Entity\Codepromo;
use App\Form\CodepromoType;
use App\Repository\CodepromoRepository;
use App\Repository\sCodepromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CodepromoController extends AbstractController
{

    #[Route('/codepromo', name: 'app_codepromo_index', methods: ['GET'])]
    public function index(CodepromoRepository $codepromoRepository): Response
    {
        return $this->render('codepromo/index.html.twig', [
            'codepromos' => $codepromoRepository->findAll(),
        ]);
    }

    #[Route('/codepromo/new', name: 'app_codepromo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $codepromo = new Codepromo();
        $form = $this->createForm(CodepromoType::class, $codepromo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($codepromo);
            $entityManager->flush();

            return $this->redirectToRoute('app_codepromo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codepromo/new.html.twig', [
            'codepromo' => $codepromo,
            'form' => $form,
        ]);
    }

    #[Route('/codepromo/{id}', name: 'app_codepromo_show', methods: ['GET'])]
    public function show(Codepromo $codepromo): Response
    {
        return $this->render('codepromo/show.html.twig', [
            'codepromo' => $codepromo,
        ]);
    }

    #[Route('/codepromo/{id}/edit', name: 'app_codepromo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Codepromo $codepromo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CodepromoType::class, $codepromo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_codepromo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codepromo/edit.html.twig', [
            'codepromo' => $codepromo,
            'form' => $form,
        ]);
    }

    #[Route('/codepromo/{id}', name: 'app_codepromo_delete', methods: ['POST'])]
    public function delete(Request $request, Codepromo $codepromo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$codepromo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($codepromo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_codepromo_index', [], Response::HTTP_SEE_OTHER);
    }
}
