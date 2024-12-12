<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

class SupplierController extends AbstractController
{
    #[Route('/supplier', name: 'supplier_index')]
    public function index(SupplierRepository $supplierRepository): Response
    {
        $suppliers = $supplierRepository->findAll();
        return $this->render('supplier/index.html.twig', ["suppliers" => $suppliers]);
    }

    #[Route(path: '/supplier/{id}', name: 'supplier_show')]
    public function show(Supplier $supplier): Response{
        return $this->render('supplier/show.html.twig', ['supplier' => $supplier]);
    }

    #[Route(path: '/supplier/new', name: 'supplier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplier->setCreatedAt(now());
            $supplier->setUpdatedAt(now());
            $entityManager->persist($supplier);
            $entityManager->flush();

            return $this->redirectToRoute('supplier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/supplier/{id}/edit', name: 'supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response{

        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplier->setUpdatedAt(now());
            $entityManager->persist($supplier);
            $entityManager->flush();

            return $this->redirectToRoute('supplier_show', ['id' => $supplier->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('supplier/new.html.twig', [
            'supplier' => $supplier,
            'form' => $form,
        ]);
    }

    #[Route(path: '/supplier/{id}/delete', name: 'supplier_delete')]
    public function delete(Supplier $supplier, EntityManagerInterface $entityManager){
        $supplierVehicles = $supplier->getVehicles();

        //retire la référence du fournisseur de tout les véhicules
        foreach($supplierVehicles as $vehicle){
            $vehicle->setSupplier(null);
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        return $this->redirectToRoute('supplier_index', []);
    }
}
