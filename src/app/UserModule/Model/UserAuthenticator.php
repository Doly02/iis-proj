<?php

namespace App\UserModule\Model;

use Nette\Security\Authenticator;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;
use Nette\Database\Explorer;
use Nette\Security\Passwords;
use Tracy\Debugger;

class UserAuthenticator implements Authenticator
{
    private $database;
    private $passwords;

    public function __construct(Explorer $database, Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    /**
     * Authenticates users by email and password
     *
     * @param string $user User login name (in this case email)
     * @param string $password User password
     * @return SimpleIdentity
     * @throws AuthenticationException If the login fails
     */
    public function authenticate(string $user, string $password) : SimpleIdentity
    {
        Debugger::barDump($user, 'User'); // Tracy Debugger výpis
        Debugger::barDump($password, 'Password');

        // Retrieve user from database by email
        $userRow = $this->database->table('users')
            ->where('email', $user)
            ->fetch();

        if (!$userRow)
        {
            throw new AuthenticationException('User not found.');
        }

        // Password verification
        /*
        if ($password !== $userRow->password)
        {
            dump($userRow->password);
            dump($password);
            throw new AuthenticationException('Invalid password.');
        }*/

        // Method For Hashed Password
        if (!$this->passwords->verify($password, $userRow->password))
        {
            dump($userRow->password);
            dump($password);
            throw new AuthenticationException('Invalid password.');
        }


        // Verify if the password needs to be reset (e.g. older hashing algorithm)
        if ($this->passwords->needsRehash($userRow->password))
        {
            $userRow->update([
                'password' => $this->passwords->hash($password),
            ]);
        }

        // TODO: Default Role as User
        $role = $userRow->role ?? 'user';

        // Create an identity for the logged-in user
        return new SimpleIdentity($userRow->id, $role, [
            'email' => $userRow->email,
            'name' => $userRow->name,
        ]);
    }
}