<?php

namespace App\Service;
use App\Entity\Product;
use App\Service\BaseService;

class ProductService extends BaseService {
    public function getEntityClass(): string
    {
        return Product::class;
    }
} 