<?php

namespace App\Service;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseService
{

    public function __construct(private EntityManagerInterface $entityManager){}

    public function create($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function read($id)
    {
        return $this->getRepository()->find($id);
    }

    public function readAll($page=null, $limit=null, $type=null){

        $filter = [];
        if(!is_null($type))
            $filter['type'] = $type;

        if(!is_null($page) && !is_null($limit))
            return $this->getRepository()->findBy($filter, null, $limit, ($page-1)*$limit);

        return $this->getRepository()->findBy($filter, null, $limit, $page);
    }

    public function update($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    abstract public function getEntityClass(): string;
    
    protected function getRepository()
    {
        return $this->entityManager->getRepository($this->getEntityClass());
    }

}