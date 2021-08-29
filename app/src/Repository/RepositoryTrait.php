<?php

namespace App\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

trait RepositoryTrait
{
    /**
     * @param object $data
     * @param bool $flush
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(object $data, bool $flush = true)
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