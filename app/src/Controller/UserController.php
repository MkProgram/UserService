<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

#[Route('/api/user', name: 'user_')]
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


    /**
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Returns a collection of users",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @return JsonResponse
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function indexAction(): JsonResponse
    {
        return $this->json(
            $this->userRepository->findAll()
        );
    }

    /**
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Returns a specific user.",
     *     @Model(type=User::class)
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID for the specific user.",
     *     @OA\Schema(type="integer")
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_item', methods: ['GET'])]
    public function getItemAction(Request $request): JsonResponse
    {
        $id = $request->get("id");
        return $this->json(
            $this->userRepository->find($id)
        );
    }

    /**
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Creates a new user.",
     *     @Model(type=User::class)
     * )
     * @OA\RequestBody(
     *      @Model(type=User::class, groups={"user:write"})
     * )
     *
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
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Updates a specific user.",
     *     @Model(type=User::class)
     * )
     * @OA\RequestBody(
     *      @Model(type=User::class, groups={"user:write"})
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID for the specific user.",
     *     @OA\Schema(type="integer")
     * )
     *
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
     * @OA\Tag(name="User")
     * @OA\Response(
     *     response=200,
     *     description="Deletes a specific user.",
     *     @Model(type=User::class)
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="ID for the specific user.",
     *     @OA\Schema(type="integer")
     * )
     *
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