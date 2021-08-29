<?php

namespace App\Tests\Functional\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserApiTest extends WebTestCase
{

    private KernelBrowser $client;

    /**
     * @param array $userData
     * @param User $user
     */
    public function assertUserData(User $user, array $userData): void
    {
        $this->assertArrayHasKey("email", $userData);
        $this->assertEquals($user->getEmail(), $userData["email"]);
        $this->assertArrayHasKey("userName", $userData);
        $this->assertEquals($user->getUserName(), $userData["userName"]);
        $this->assertArrayHasKey("firstName", $userData);
        $this->assertEquals($user->getFirstName(), $userData["firstName"]);
        $this->assertArrayHasKey("lastName", $userData);
        $this->assertEquals($user->getLastName(), $userData["lastName"]);
    }

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }


    public function testCanGetAllUser() {
        $this->client->request('GET', '/user/');
        $this->assertResponseIsSuccessful();
    }

    public function testCanGetOneUser() {
        /** @var UserRepository $repository */
        $repository = static::getContainer()->get(UserRepository::class);
        $user = $repository->findOneBy(['email' => "example@gmail.com"]);

        $this->client->request('GET', '/user/'. $user->getId() );
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $userData = json_decode($response->getContent(), true);
        $this->assertUserData($user, $userData);
    }

    public function testCanCreateUser() {
        $userData = [
            "email" => "eren.yeager@gmail.com",
            "firstName" => "Eren",
            "lastName" => "Yeager",
            "userName" => "ErenYeager"
        ];
        $this->client->request("POST",
            "/user/",
            [],
            [],
            [
                "CONTENT_TYPE" => "application/json"
            ],
            json_encode($userData)
        );
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $repository = static::getContainer()->get(UserRepository::class);
        $this->assertArrayHasKey("id", $responseData);
        $user = $repository->find($responseData["id"]);
        $this->assertNotNull($user);
        # Asserting the user data that has been sent to the server
        $this->assertUserData($user, $userData);
        # Asserting the user data that has been returned by the server
        $this->assertUserData($user, $responseData);
        return $responseData;
    }

    /**
     * @param array $userData
     * @depends testCanCreateUser
     */
    public function testCanUpdateUser(array $userData)
    {
        $this->assertArrayHasKey("id", $userData);
        $changeData = [
            "firstName" => "Eren",
            "lastName" => "Jäger",
            "userName" => "ErenJäger",
            "email" => "eren.jäger@gmail.com"
        ];
        $this->client->request(
            "PUT",
            "/user/".$userData['id'],
            [],
            [],
            [
                "CONTENT_TYPE" => "application/json"
            ],
            json_encode($changeData)
        );
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $repository = static::getContainer()->get(UserRepository::class);
        $user = $repository->find($userData["id"]);
        $this->assertNotNull($user);
        $this->assertUserData($user, $changeData);
        $this->assertUserData($user, $responseData);
    }

    /**
     * @depends testCanCreateUser
     */
    public function testCanDeleteUser(array $userData) {
        $this->assertArrayHasKey("id", $userData);
        $this->client->request(
            "DELETE",
            "/user/".$userData['id']
        );
        $this->assertResponseIsSuccessful();
        $repository = static::getContainer()->get(UserRepository::class);
        $user = $repository->find($userData["id"]);
        $this->assertNull($user);
    }
}