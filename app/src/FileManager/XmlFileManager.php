<?php

namespace App\FileManager;

use App\Entity\User;
use App\Model\UserCollection;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class XmlFileManager implements FileManagerInterface
{

    private SerializerInterface $serializer;
    private string $filePath;

    private UserCollection $collection;

    public function __construct(SerializerInterface $serializer, string $filePath)
    {
        $this->serializer = $serializer;
        $this->filePath = $filePath;
    }

    private function fetchData(): UserCollection
    {
        $fileData = file_get_contents($this->filePath);
        $this->collection = $this->serializer->deserialize($fileData, UserCollection::class, "xml");
        return $this->collection;
    }

    public function save(): void
    {
        $fileData = $this->serializer->serialize($this->collection, "xml");
        file_put_contents($this->filePath, $fileData);
    }


    public function getAll(): array
    {
        if($this->collection === null) {
            $this->fetchData();
        }
        return $this->collection->getUser();
    }

    public function get($id): User
    {
        if($this->collection === null) {
            $this->fetchData();
        }
        return $this->collection->getById($id);
    }

    public function getOneBy(array $criteria): User
    {
        if($this->collection === null) {
            $this->fetchData();
        }
        return $this->collection->getOneBy($criteria);
    }

    public function getBy(array $criteria) {
        if($this->collection === null) {
            $this->fetchData();
        }
        return $this->collection->getBy($criteria);
    }

    public function add(object $data): void {
        if($this->collection === null) {
            $this->fetchData();
        }
        if($data->getId() === null) {
            $lastId = 0;
            foreach($this->collection->getUser() as $user) {
                $userId = $user->getId();
                if($userId > $lastId) {
                    $lastId = $userId;
                }
            }
            $data->setId(++$lastId);
            /** @var User $data */
            $this->collection->addUser($data);
        }
    }

    public function remove(object $data): void
    {
        if($this->collection === null) {
            $this->fetchData();
        }
        /** @var User $data */
        $this->collection->removeUser($data);
    }
}