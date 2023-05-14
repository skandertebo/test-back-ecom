<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use App\Service\SaleService;
use App\Service\TypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{

    public function __construct(
        private ProductService $productService,
        private TypeService $typeService,
        private SaleService $saleService
    ) {
    }

    #[Route('/product', name: 'app_product', methods: ['GET'])]
    public function getProducts(Request $req): Response
    {
        $limit = $req->query->get('limit', null);
        $page = $req->query->get('page', null);
        $products = $this->productService->readAll($page, $limit);
        return $this->json($products);
    }

    #[Route('/product/{id}', name: 'app_product_get', methods: ['GET'])]
    public function getProduct(int $id): Response
    {
        $product = $this->productService->read($id);
        return $this->json($product);
    }

    #[Route('/product', name: 'app_product_create', methods: ['POST'])]
    public function createProduct(Request $req): Response
    {
        $data = json_decode($req->getContent(), true);
        if(!isset($data['name']) || !isset($data['basePrice']) || !isset($data['type']) || !isset($data['stockQuantity'])){
            return $this->json(['error' => 'Missing parameters'], 400);
        }
        $type = $this->typeService->read($data['type']);
        if(is_null($type)){
            return $this->json(['error' => 'Type not found'], 404);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setBasePrice($data['basePrice']);
        $product->setType($type);
        $product->setStockQuantity($data['stockQuantity']);
        $this->productService->create($product);

        return $this->json($product);
    }

    #[Route('/product/{id}', name: 'app_product_update', methods: ['PUT'])]
    public function updateProduct(int $id, Request $req): Response
    {
        $data = json_decode($req->getContent(), true);
        $product = $this->productService->read($id);
        if(is_null($product)){
            return $this->json(['error' => 'Product not found'], 404);
        }
        if(isset($data['name'])){
            $product->setName($data['name']);
        }
        if(isset($data['basePrice'])){
            $product->setBasePrice($data['basePrice']);
        }
        if(isset($data['type'])){
            $type = $this->typeService->read($data['type']);
            if(is_null($type)){
                return $this->json(['error' => 'Type not found'], 404);
            }
            $product->setType($type);
        }
        if(isset($data['sale'])){
            $sale = $this->saleService->read($data['sale']);
            if(is_null($sale)){
                return $this->json(['error' => 'Sale not found'], 404);
            }
            $product->setSale($data['sale']);
        }
        if(isset($data['stockQuantity'])){
            $product->setStockQuantity($data['stockQuantity']);
        }
        $this->productService->update($product);
        return $this->json($product);
    }

    #[Route('/product/{id}', name: 'app_product_delete', methods: ['DELETE'])]
    public function deleteProduct(int $id): Response
    {
        $product = $this->productService->read($id);
        if(is_null($product)){
            return $this->json(['error' => 'Product not found'], 404);
        }
        $this->productService->delete($product);
        return $this->json(['success' => 'Product deleted']);
    }

}
