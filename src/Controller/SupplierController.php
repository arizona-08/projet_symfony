<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

class SupplierController extends AbstractController
{
    #[Route('/supplier', name: 'supplier_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, SupplierRepository $supplierRepository, PaginatorInterface $paginator): Response
    {
        $suppliers = $supplierRepository->findAll();
        $queryBuilder = $entityManager->getRepository(Supplier::class)->createQueryBuilder('s');

        $suppliers = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('supplier/index.html.twig', ["suppliers" => $suppliers]);
    }

    #[Route(path: '/supplier/{id}', name: 'supplier_show', methods: ['GET'])]
    public function show(Supplier $supplier): Response
    {
        return $this->render('supplier/show.html.twig', ['supplier' => $supplier]);
    }


    #[Route(path: '/supplier/{id}/vehicles', name: 'supplier_showvehicle', methods: ['GET'])]
    public function showVehicle(Supplier $supplier): Response
    {
        return $this->render('supplier/showvehicle.html.twig', ['supplier' => $supplier]);
    }

    #[Route(path: '/supplier/create', name: 'supplier_create', methods: ['GET', 'POST'], priority: 10)]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
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

        return $this->render('supplier/create.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false,
        ]);
    }

    #[Route('/suppliers/{id}/edit', name: 'supplier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Supplier $supplier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplier->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('supplier_index');
        }

        return $this->render('supplier/edit.html.twig', [
            'form' => $form->createView(),
            'supplier' => $supplier,
            'isEdit' => true,
        ]);
    }

    #[Route(path: '/supplier/{id}/delete', name: 'supplier_delete')]
    public function delete(Supplier $supplier, EntityManagerInterface $entityManager)
    {
        $supplierVehicles = $supplier->getVehicles();

        foreach ($supplierVehicles as $vehicle) {
            $vehicle->setSupplier(null);
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        return $this->redirectToRoute('supplier_index', []);
    }
}
