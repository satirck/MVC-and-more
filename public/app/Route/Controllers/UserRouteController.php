<?php

declare(strict_types=1);

namespace App\Route\Controllers;

use App\Route\Attributes\{DomainKeyAttribute, MethodRouteAttribute};

use App\Models\User;
use App\Repository\Exceptions\EntityNotFoundException;
use App\Repository\UserRepository;

use App\Route\Exceptions\StatusErrorException;
use Exception;
use http\Env\Response;

#[DomainKeyAttribute('/users')]
class UserRouteController implements RouteControllerInterface
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    #[MethodRouteAttribute('GET', '/users')]
    public function index(): void
    {
        $users = $this->userRepository->getAll();

        echo json_encode($users);
    }

    /**
     * @throws Exception
     */
    #[MethodRouteAttribute('GET', '/users/{id}')]
    public function getUserById(int $id): void
    {
        try {
            $user = $this->userRepository->getById($id);
        }catch (EntityNotFoundException $exception){
            throw new StatusErrorException( $exception->getMessage(), 404);
        }

        echo json_encode($user->jsonSerialize());
    }

    #[MethodRouteAttribute('POST', '/users')]
    public function createUser(User $user): void
    {
        $savedUser = $this->userRepository->save(json_encode($user));

        echo $savedUser;
    }


}
