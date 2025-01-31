<?php

namespace apprdv\core\services\auth;

use Ramsey\Uuid\Uuid;
use apprdv\core\domain\entities\user\User;
use apprdv\core\dto\auth\AuthDTO;
use apprdv\core\dto\credentials\CredentialsDTO;
use apprdv\core\repositoryInterfaces\RepositoryUserInterface;

class ServiceAuthn implements ServiceAuthnInterface
{
    private RepositoryUserInterface $repository;

    public function __construct(RepositoryUserInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createUser(CredentialsDTO $credentials, int $role): string
    {
        $user = new User($credentials->getLogin(), $credentials->getPassword(), $role);
        $this->repository->save($user);
        return $user->getID();
    }

    public function byCredentials(CredentialsDTO $credentials): AuthDTO
    {
        $user = $this->repository->byLogin($credentials->getLogin());
        if ($user->getPassword() === $credentials->getPassword()) {
            return new AuthDTO($user->getID(),$user->getLogin(), $user->getPassword());
        }
        throw new \Exception('Invalid credentials');
    }
}