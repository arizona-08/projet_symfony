<?php

namespace App\Controller;

use App\Entity\Config;

use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Equipment;
use App\Repository\KitRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\EquipmentRepository;

#[Route('/config')]
class ConfigController extends AbstractController
{
    #[Route('/edit/{id}', name: 'config_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Config $config, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Configuration mise à jour avec succès.');

            return $this->redirectToRoute('config_index');
        }

        return $this->render('config/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/config/{id}/delete', name: 'config_delete', methods: ['POST'])]
    public function delete(Config $config, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $config->getId(), $request->request->get('_token'))) {
            $entityManager->remove($config);
            $entityManager->flush();
    
            $this->addFlash('success', 'Configuration supprimée avec succès.');
        }
    
        return $this->redirectToRoute('config_index');
    }
    
    #[Route('/get-kit-accessories', name: 'config_get_kit_accessories', methods: ['GET'])]
    public function getKitAccessories(Request $request, KitRepository $kitRepository): JsonResponse
    {
        $kitId = $request->query->get('kit');
    
        if (!$kitId) {
            return new JsonResponse([]);
        }
    
        $kit = $kitRepository->find($kitId);
    
        if (!$kit) {
            return new JsonResponse(['error' => 'Kit not found'], 404);
        }
    
        $accessories = $kit->getAccessory();
    
        $response = array_map(fn($accessory) => [
            'id' => $accessory->getId(),
            'name' => $accessory->getName(),
        ], $accessories->toArray());
    
        return new JsonResponse($response);
    }

    #[Route('/create', name: 'config_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, KitRepository $kitRepository): Response
    {
        $user = $this->getUser();

        // pas nécessaire si on a déjà un firewall qui gère les rôles (voir config/packages/security.yaml)
        // if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
        //     throw $this->createAccessDeniedException('You are not allowed to create a configuration.');
        // }

        $config = new Config();
        $config->setClient($user);

        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedKitId = $form->get('kit')->getData();
            $kit = $kitRepository->find($selectedKitId);

            if ($kit) {
                $config->setKit($kit);
            }

            $entityManager->persist($config);
            $entityManager->flush();

            $this->addFlash('success', 'Configuration created successfully!');

            return $this->redirectToRoute('config_index');
        }

        return $this->render('config/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'config_index', methods: ['GET'])]
    public function index(ConfigRepository $configRepository): Response
    {
        $user = $this->getUser();

        // pas nécessaire si on a déjà un firewall qui gère les rôles (voir config/packages/security.yaml)
        // if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
        //     throw $this->createAccessDeniedException('You are not allowed to view configurations.');
        // }

        $configs = $configRepository->findBy(['client' => $user]);
    

        return $this->render('config/index.html.twig', [
            'configs' => $configs,
        ]);
    }

}