<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SupplierController extends AbstractController
{
    #[Route('/supplier', name: 'supplier')]
    public function index(): Response
    {
        return $this->render('supplier/index.html.twig', []);
    }
}
