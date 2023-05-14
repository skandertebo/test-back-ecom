<?php

namespace App\Controller;

use App\Entity\Type;
use App\Service\TypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{

    public function __construct( private TypeService $typeService) {}

    #[Route('/type', name: 'app_type', methods: ['GET'])]
    public function getAll(): Response
    {
        $types = $this->typeService->readAll();
        return $this->json($types);
    }

    #[Route('/type/{id}', name: 'app_type_get', methods: ['GET'])]
    public function get(int $id): Response
    {
        $type = $this->typeService->read($id);
        return $this->json($type);
    }

    #[Route('/type', name: 'app_type_create', methods: ['POST'])]
    public function create(Request $request): Response{
        $data = json_decode($request->getContent(), true);
        if(!isset($data['name'])){
            return $this->json(['error' => 'Missing parameters'], 400);
        }
        $type = new Type();
        $type->setName($data['name']);
        $this->typeService->create($type);
        return $this->json($type);
    }

    #[Route('/type/{id}', name: 'app_type_update', methods: ['PUT'])]
    public function update(int $id, Request $request): Response{
        $data = json_decode($request->getContent(), true);
        $type = $this->typeService->read($id);
        if(is_null($type)){
            return $this->json(['error' => 'Type not found'], 404);
        }
        if(isset($data['name'])){
            $type->setName($data['name']);
        }
        $this->typeService->update($type);
        return $this->json($type);
    }

    #[Route('/type/{id}', name: 'app_type_delete', methods: ['DELETE'])]
    public function delete(int $id): Response{
        $type = $this->typeService->read($id);
        if(is_null($type)){
            return $this->json(['error' => 'Type not found'], 404);
        }
        $this->typeService->delete($type);
        return $this->json(['message' => 'Type deleted']);
    }
}
