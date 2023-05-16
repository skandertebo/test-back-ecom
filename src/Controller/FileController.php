<?php

namespace App\Controller;

use Exception;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

use function App\createErrorResponse;

#[Route('public/', name: 'app_file')]
class FileController extends AbstractController
{
  #[Route('image/{filename}', name: '_images')]
  
  public function serveImage($filename): Response
  {
    $path = '../public/images/'.$filename;
    
    try {
        $response = new BinaryFileResponse($path);
        $response->headers->set('Content-Type', 'image/jpg');
        return $response;
    } catch (Exception $e) {
        throw new FileNotFoundException('File not found');
    }
  }

}