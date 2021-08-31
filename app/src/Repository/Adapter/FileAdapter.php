<?php

namespace App\Repository\Adapter;

use App\FileManager\FileManagerInterface;

class FileAdapter implements AdapterInterface
{


    private FileManagerInterface $manager;

    public function __construct(FileManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function find($id)
    {
        return $this->manager->get($id);
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->manager->getOneBy($criteria);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->manager->getAll();
    }

    public function findBy(array $criteria, array $orderBy = null)
    {
        return $this->manager->getBy($criteria);
    }

    public function add(object $data, bool $flush = true): void
    {
        $this->manager->add($data);

        if($flush) {
            $this->manager->save();
        }
    }

    public function remove(object $data, bool $flush = true): void
    {
        $this->manager->remove($data);
    }
}