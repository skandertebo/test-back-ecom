<?php

namespace App\Service;
use App\Entity\Type;
use App\Service\BaseService;

class TypeService extends BaseService {
    public function getEntityClass(): string
    {
        return Type::class;
    }
} 