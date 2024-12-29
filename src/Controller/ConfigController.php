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
            'name' => $accessory,
        ], $accessories);

        return new JsonResponse($response);
    }

    #[Route('/create', name: 'config_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, KitRepository $kitRepository): Response
    {
        $user = $this->getUser();

        if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
            throw $this->createAccessDeniedException('You are not allowed to create a configuration.');
        }

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

        if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
            throw $this->createAccessDeniedException('You are not allowed to view configurations.');
        }

        $configs = $configRepository->findBy(['client' => $user]);

        return $this->render('config/index.html.twig', [
            'configs' => $configs,
        ]);
    }

}