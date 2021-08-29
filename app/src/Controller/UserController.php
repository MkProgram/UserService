<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer
    )
    {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function indexAction(): JsonResponse
    {
        return $this->json(
            $this->userRepository->findAll()
        );
    }

    #[Route('/{id}', name: 'get_item', methods: ['GET'])]
    public function getItemAction(Request $request): JsonResponse
    {
        $id = $request->get("id");
        return $this->json(
            $this->userRepository->find($id)
        );
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/', name: 'create', methods: ['POST'])]
    public function createAction(Request $request): JsonResponse
    {
        $body = $request->getContent();
        $user = $this->serializer->deserialize($body, User::class, 'json');

        $this->userRepository->add($user);
        return $this->json($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function updateAction(Request $request): JsonResponse
    {
        $user = $this->userRepository->find($request->get("id"));
        $changeData = json_decode($request->getContent(), true);
        $user->setEmail($changeData["email"]);
        $user->setUserName($changeData["userName"]);
        $user->setFirstName($changeData["firstName"]);
        $user->setLastName($changeData["lastName"]);

        $this->userRepository->add($user);
        return $this->json($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route('/{id}', name: 'delete', methods: ["DELETE"])]
    public function removeAction(Request $request): JsonResponse {
        $user = $this->userRepository->find($request->get("id"));
        $this->userRepository->remove($user);
        return $this->json($user);
    }


}