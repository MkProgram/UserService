<?php

namespace App\Repository\Adapter;

class XmlFileAdapter implements AdapterInterface
{

    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        // TODO: Implement findOneBy() method.
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    public function findBy(array $criteria, array $orderBy = null)
    {
        // TODO: Implement findBy() method.
    }

    public function add(object $data, bool $flush = true): void
    {
        // TODO: Implement add() method.
    }

    public function remove(object $data, bool $flush = true): void
    {
        // TODO: Implement remove() method.
    }
}