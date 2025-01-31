<?php

namespace apprdv\core\dto\credentials;

use apprdv\core\dto\DTO;

class CredentialsDTO extends DTO
{
    protected string $login;
    protected string $password;
    protected int $role;

    public function __construct(string $login, string $password, int $role)
    {
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): int
    {
        return $this->role;
    }
}