<?php

namespace App\Controller;

use App\Service\SaleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sale;
class SaleController extends AbstractController
{

    public function __construct(private SaleService $saleService){}
    #[Route('/sale', name: 'app_sales', methods: ['GET'])]
    public function getAll(){
        $sales = $this->saleService->readAll();
        return $this->json($sales);
    }

    #[Route('/sale/{id}', name: 'app_sales_get', methods: ['GET'])]
    public function get(int $id): Response{
        $sale = $this->saleService->read($id);
        return $this->json($sale);
    }

    #[Route('/sale', name: 'app_sales_create', methods: ['POST'])]
    public function create(Request $request): Response{
        $data = json_decode($request->getContent(), true);
        if(!isset($data['rate'])){
            return $this->json(['error' => 'Missing parameters'], 400);
        }
        $sale = new Sale();
        $sale->setRate($data['rate'])
            ->setExpired($data['expired'])
            ->setName($data['name'])
            ->setExpireDate(
                new \DateTime($data['expireDate']
            )
            );
        $this->saleService->create($sale);
        return $this->json($sale);
    }

    #[Route('/sale/{id}', name: 'app_sales_update', methods: ['PUT'])]
    public function update(int $id, Request $request): Response{
        $sale = $this->saleService->read($id);
        if(is_null($sale)){
            return $this->json(['error' => 'Sale not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if(isset($data['rate'])){
            $sale->setRate($data['rate']);
        }
        if(isset($data['expired'])){
            $sale->setExpired($data['expired']);
        }
        $this->saleService->update($sale);
        return $this->json($sale);
    }

    #[Route('/sale/{id}', name: 'app_sales_delete', methods: ['DELETE'])]
    public function delete(int $id): Response{
        $sale = $this->saleService->read($id);
        if(is_null($sale)){
            return $this->json(['error' => 'Sale not found'], 404);
        }
        $this->saleService->delete($sale);
        return $this->json(['message' => 'Sale deleted']);
    }
}
