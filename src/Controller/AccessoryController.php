<?php

namespace App\Controller;

use App\Entity\Accessory;
use App\Form\AccessoryType;
use App\Repository\AccessoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/accessory')]
final class AccessoryController extends AbstractController
{
    #[Route(name: 'app_accessory_index', methods: ['GET'])]
    public function index(AccessoryRepository $accessoryRepository): Response
    {
        return $this->render('accessory/index.html.twig', [
            'accessories' => $accessoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_accessory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accessory = new Accessory();
        $form = $this->createForm(AccessoryType::class, $accessory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($accessory);
            $entityManager->flush();

            $this->addFlash('success', 'Accessoire créé avec succès.');
            return $this->redirectToRoute('app_accessory_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Une erreur est survenue lors de la création de l\'accessoire.');
        }

        return $this->render('accessory/new.html.twig', [
            'accessory' => $accessory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accessory_show', methods: ['GET'])]
    public function show(Accessory $accessory): Response
    {
        return $this->render('accessory/show.html.twig', [
            'accessory' => $accessory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accessory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accessory $accessory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccessoryType::class, $accessory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Accessoire modifié avec succès.');
            return $this->redirectToRoute('app_accessory_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Une erreur est survenue lors de la modification de l\'accessoire.');
        }

        return $this->render('accessory/edit.html.twig', [
            'accessory' => $accessory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accessory_delete', methods: ['POST'])]
    public function delete(Request $request, Accessory $accessory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $accessory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($accessory);
            $entityManager->flush();

            $this->addFlash('success', 'Accessoire supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression : jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_accessory_index', [], Response::HTTP_SEE_OTHER);
    }
}
