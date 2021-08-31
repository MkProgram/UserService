<?php

namespace App\FileManager;

interface FileManagerInterface
{
    public function save(): void;
    public function getAll(): array;
    public function get($id);
    public function getOneBy(array $criteria);
    public function getBy(array $criteria);

    public function add(object $data);

    public function remove(object $data);
}