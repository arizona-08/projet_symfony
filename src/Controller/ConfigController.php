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

#[Route('/config')]
class ConfigController extends AbstractController
{
    #[Route('/create', name: 'config_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est VIP
        if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
            throw $this->createAccessDeniedException('You are not allowed to create a configuration.');
        }

        $config = new Config();
        $config->setClient($user);

        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($config);
            $entityManager->flush();

            $this->addFlash('success', 'Configuration created successfully!');

            return $this->redirectToRoute('config_list'); // Remplacez par la route appropriée
        }

        return $this->render('config/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'config_index')]
    public function index(ConfigRepository $configRepository): Response
    {
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est VIP
        if (!$user || !in_array('ROLE_VIP', $user->getRoles())) {
            throw $this->createAccessDeniedException('You are not allowed to view configurations.');
        }

        $configs = $configRepository->findBy(['client' => $user]);

        return $this->render('config/index.html.twig', [
            'configs' => $configs,
        ]);
    }



}