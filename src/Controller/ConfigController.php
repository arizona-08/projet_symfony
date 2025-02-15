<?php

namespace App\Controller;

use App\Entity\Config;
use App\Other\EasterEgg;
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
use App\Repository\LocationRepository;
use App\Repository\VehicleRepository;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/config')]
class ConfigController extends AbstractController
{
    #[Route('/', name: 'config_index', methods: ['GET'])]
    public function index(ConfigRepository $configRepository): Response
    {
        $user = $this->getUser();

        $configs = $configRepository->findBy(['client' => $user]);

        $easterEgg = new EasterEgg();
        $easterEggPhrase = $easterEgg->getPhrase();

        return $this->render('config/index.html.twig', [
            'configs' => $configs,
            'easterEggPhrase' => $easterEggPhrase,
        ]);
    }
    
    #[Route('/create', name: 'config_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        VehicleRepository $vehicleRepository,
        LocationRepository $locationRepository
    ): Response {
        $config = new Config();
        $config->setClient($this->getUser());
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        $vehicles = $vehicleRepository->findAll();
        $vehiclesAvailability = [];
        foreach ($vehicles as $vehicle) {
            $reservations = $locationRepository->findReservationsForVehicle($vehicle->getId());
            $vehiclesAvailability[$vehicle->getId()] = $this->formatAvailabilityMessage($reservations);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($config);
                $entityManager->flush();

                $this->addFlash('success', 'Configuration créée avec succès !');
                return $this->redirectToRoute('config_index');
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Le véhicule sélectionné possède déjà une configuration.');
            }
        }

        return $this->render('config/create.html.twig', [
            'form' => $form->createView(),
            'vehicles' => $vehicles,
            'vehiclesAvailability' => $vehiclesAvailability,
        ]);
    }



    private function formatAvailabilityMessage(array $reservations): string
    {
        if (empty($reservations)) {
            return 'Toutes les dates sont disponibles pour ce véhicule.';
        }

        $periods = array_map(function ($reservation) {
            return sprintf(
                'du %s au %s',
                $reservation->getStartDate()->format('d/m/Y'),
                $reservation->getEndDate()->format('d/m/Y')
            );
        }, $reservations);

        return 'Ce véhicule est réservé pour les dates suivantes : ' . implode(', ', $periods) . '.';
    }

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

}
