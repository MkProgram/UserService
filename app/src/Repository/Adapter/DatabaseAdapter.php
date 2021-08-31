<?php

namespace App\Repository\Adapter;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DatabaseAdapter extends ServiceEntityRepository implements AdapterInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param object $data
     * @param bool $flush
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(object $data, bool $flush = true): void
    {
        $this->_em->persist($data);
        if($flush === true) {
            $this->_em->flush();
        }
    }

    /**
     * @param object $data
     * @param bool $flush
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(object $data, bool $flush = true): void
    {
        $this->_em->remove($data);
        if($flush === true) {
            $this->_em->flush();
        }
    }
}