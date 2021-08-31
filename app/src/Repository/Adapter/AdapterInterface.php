<?php

namespace App\Repository\Adapter;

interface AdapterInterface
{
    public function find($id);
    public function findOneBy(array $criteria, array $orderBy = null);
    public function findAll();
    public function findBy(array $criteria, array $orderBy = null);
    public function add(object $data, bool $flush = true): void;
    public function remove(object $data, bool $flush = true): void;
}