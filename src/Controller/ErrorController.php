<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ErrorController extends AbstractController
{
    public function show(Request $request, FlattenException $exception): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'status_code' => $exception->getStatusCode(),
            'status_text' => Response::$statusTexts[$exception->getStatusCode()] ?? '',
            'message' => $exception->getMessage(),
            'exception' => $exception,
        ]);
    }
}
