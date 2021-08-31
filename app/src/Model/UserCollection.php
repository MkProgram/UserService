<?php

namespace App\Model;

use App\Entity\User;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;

class UserCollection
{
    /**
     * @var User[]
     */
    #[Groups(["user:read"])]
    private array $user;

    public function __construct() {
        $this->user = [];
    }

    /**
     * @return User[]
     */
    public function getUser(): array
    {
        return $this->user;
    }

    /**
     * @param User[] $user
     */
    public function setUser(array $user): void
    {
        $this->user = $user;
    }

    public function addUser(User $user): void
    {
        $this->user[] = $user;
    }

    public function removeUser(User $user): void
    {
        $key = array_search($user, $this->user, true);

        if ($key === false) {
            return;
        }

        unset($this->user[$key]);
    }

    #[Pure] public function getById($id): ?User
    {
        foreach($this->user as $user) {
            if($user->getId() === $id) {
                return $user;
            }
        }
        return null;
    }

    public function getOneBy(array $criteria): ?User
    {
        foreach($this->user as $user) {
            $fulfilled = [];
            foreach($criteria as $col => $value) {
                $getter = 'get'.ucwords($col);
                $fulfilled[] = $user->{$getter}() === $value;
            }
            if(!in_array(false, $fulfilled)) {
                return $user;
            }
        }
        return null;
    }

    public function getBy(array $criteria): array
    {
        return array_filter($this->user, function(User $user) use ($criteria) {
            $fulfilled = [];
            foreach($criteria as $col => $value) {
                $getter = 'get'.ucwords($col);
                $fulfilled[] = $user->{$getter}() === $value;
            }
            return !in_array(false, $fulfilled);
        });
    }
}