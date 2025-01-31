<?php

namespace apprdv\core\dto\auth;

use apprdv\core\dto\DTO;

class AuthDTO extends DTO
{
    protected string $ID;
    protected string $login;
    protected string $password;
    protected string $createToken;
    protected string $refreshToken;

    public function __construct(string $ID, string $login, string $password, ?string $createToken, ?string $refreshToken)
    {
        $this->ID = $ID;
        $this->login = $login;
        $this->password = $password;
        $this->createToken = $createToken;
        $this->refreshToken = $refreshToken;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function getID(): string
    {
        return $this->ID;
    }
    public function getCreateToken(): string
    {
        return $this->createToken;
    }
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->ID,
            'login' => $this->login,
            'password' => $this->password,
            'createToken' => $this->createToken,
            'refreshToken' => $this->refreshToken
        ];
    }
}