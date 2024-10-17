<?php

namespace App\User\Presenters;

use Nette\Security\Authenticator;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;

class FakeAuthenticator implements Authenticator
{
    private $users = [
        'admin' => [
            'password' => 'admin123',  // heslo natvrdo
            'role' => 'admin',
        ],
        'user' => [
            'password' => 'user123',
            'role' => 'user',
        ],
    ];

    public function authenticate(string $username, string $password): SimpleIdentity
    {
        if (!isset($this->users[$username])) {  // if user exists
            throw new AuthenticationException('User not found.');
        }

        if ($this->users[$username]['password'] !== $password) { // password validation
            throw new AuthenticationException('Invalid password.');
        }

        // if success return SimpleIndetity
        return new SimpleIdentity(
            $username,                          // ID of User
            $this->users[$username]['role'],    // User Role
            ['username' => $username]           // Another Data To Store To Identity
        );
    }
}
