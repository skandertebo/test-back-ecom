<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use App\Service\SaleService;
use App\Service\TypeService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\FileBag;

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
        $type = $req->query->get('type', null);
        $products = $this->productService->readAll($page, $limit, $type);
        return $this->json($products);
    }
    
    #[Route('/product/getInMass', name: 'app_getInMass_products', methods: ['GET'])]
    public function getInMass(Request $req){
        $ids = $req->query->get('ids');
        $idsInArray = explode(',', $ids);
        $products = [];
        foreach ($idsInArray as $id){
            $product = $this->productService->read($id);
            if(is_null($product)){
                throw new NotFoundHttpException(json_encode([
                    'message' => 'Product with id '. $id .' not found',
                    "code" => "404"
                ]));
            }
            $products[] = $product;
        }
            return $this->json($products);
    }
    
    #[Route('/product/{id}', name: 'app_product_get', methods: ['GET'])]
    public function getProduct(int $id): Response
    {
        $product = $this->productService->read($id);
        return $this->json($product);
    }

    #[Route('/product', name: 'app_product_create', methods: ['POST'])]
    public function createProduct(Request $req, LoggerInterface $logger): Response
    {
        //$data = json_decode($req->getContent(), true);
        $data = $req->request->all();
        if(!isset($data['name']) || !isset($data['basePrice']) || !isset($data['type']) || !isset($data['stockQuantity'])){
            return $this->json(['error' => 'Missing parameters'], 400);
        }
        $type = $this->typeService->read($data['type']);
        if(is_null($type)){
            return $this->json(['error' => 'Type not found'], 404);
        }
        $product = new Product();
        if(isset($data['description'])){
            $product->setDescription($data['description']);
        }
        $product->setName($data['name']);
        $product->setBasePrice($data['basePrice']);
        $product->setType($type);
        $product->setStockQuantity($data['stockQuantity']);
 
        $image = $req->files->get('image');
        if(!is_null($image)){
            $imageName = uniqid() . '.' . $image->guessExtension();
            $image->move('../public/images/', $imageName);
            $product->addImage($imageName);
        }else{
            $images = $req->files->get('images');
            if(!is_null($images)){
                foreach ($images as $image){
                    $imageName = uniqid() . '.' . $image->guessExtension();
                    $image->move('../public/images/', $imageName);
                    $product->addImage($imageName);
                }
            }
        }

        $this->productService->create($product);

        return $this->json($product);
    }

    #[Route('/product/{id}', name: 'app_product_update', methods: ['PUT'])]
    public function updateProduct(int $id, Request $req, LoggerInterface $logger): Response
    {
        $data = json_decode($req->getContent(), true);
        $logger->log('warning', json_encode($data));
        $product = $this->productService->read($id);
        if(is_null($product)){
            return $this->json(['error' => 'Product not found'], 404);
        }
        if(isset($data['name'])){
            $product->setName($data['name']);
        }
        if(isset($data['description'])){
            $product->setDescription($data['description']);
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
            if($data['sale'] == ''){
                $product->setSale(null);
            }else{
                $sale = $this->saleService->read($data['sale']);
                if(is_null($sale)){
                    return $this->json(['error' => 'Sale not found'], 404);
                }
                $product->setSale($sale);
            }
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
