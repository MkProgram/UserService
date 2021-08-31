<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Adapter\AdapterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository
{

    private AdapterInterface $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function find(int $id): ?User
    {
        return $this->adapter->find($id);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        return $this->adapter->findOneBy($criteria, $orderBy);
    }

    public function findAll(): array
    {
        return $this->adapter->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null): array
    {
        return $this->adapter->findBy($criteria, $orderBy);
    }

    /**
     * @param User $data
     * @param bool $flush
     * @return mixed
     */
    public function add(User $data, bool $flush = true): void
    {
        $this->adapter->add($data, $flush);
    }

    /**
     * @param User $data
     * @param bool $flush
     * @return mixed
     */
    public function remove(User $data, bool $flush = true): void
    {
        $this->adapter->remove($data, $flush);
    }
}
