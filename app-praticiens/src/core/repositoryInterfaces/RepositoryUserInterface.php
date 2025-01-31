<?php

namespace apppraticien\core\repositoryInterfaces;

use apppraticien\core\domain\entities\user\User;

interface RepositoryUserInterface
{
    public function save(User $user): void;
    public function byLogin(string $login): User;
}