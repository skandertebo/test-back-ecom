<?php

namespace App\Service;
use App\Entity\Sale;
use App\Service\BaseService;
class SaleService extends BaseService {
    public function getEntityClass(): string
    {
        return Sale::class;
    }
} 