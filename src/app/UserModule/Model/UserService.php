<?php

namespace App\UserModule\Model;

use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use App\UserModule\Enums\Role;
use Nette\Utils\ArrayHash;
use App\CommonModule\Model\BaseService;
use Nette\Security\SimpleIdentity;
use Exception;
use Tracy\Debugger;

final class UserService extends BaseService implements Authenticator
{
    public function getTableName(): string
    {
        return 'users';
    }

    public function getUserTable(): Selection
    {
        return $this->database->table('users');
    }
    public function getUserById(int $userId): ?ActiveRow
    {
        return $this->database->table('users')->get($userId);
    }

    public function authenticate(string $email, string $password): SimpleIdentity
    {
        $passwords = new Passwords();

        $user = $this->getTable()
            ->where('email', $email)
            ->fetch();

        $errorMessage = 'Your Credentials Do Not Match Our Records. Please Try Again.';

        if (!$user)
        {
            throw new \Nette\Security\AuthenticationException($errorMessage);
        }

        if (!$passwords->verify($password, $user->password))
        {
            throw new \Nette\Security\AuthenticationException($errorMessage);
        }

        if ($passwords->needsRehash($user->password)) {
            $user->update([
                'password' => $passwords->hash($password),
            ]);
        }

        $userData = $user->toArray();
        unset($userData['password']); // Remove The Password Safely

        return new SimpleIdentity($user->id, [$user->account_type], $userData);
    }

    // TODO Maybe Implemented getAdminTable

    public function registrateUser(ArrayHash $data, string $role) : void
    {
        $errorMessage = 'During Creation of An Account Was Error.';
        $errMessage = 'During 1 Creation of An Account Was Error.';
        $this->database->beginTransaction();

        if (Role::USER !== $role && Role::ADMIN !== $role)
        {
            throw new Exception($errMessage);
        }
        try
        {
            /* Creation Instance of Password For Hash Function */
            $passwords = new Passwords();

            Debugger::log('Trying to insert user with email: ' . $data->email);

            /* Data Insertion - one insert for all data */
            $user = $this->getTable()->insert([
                'name' => $data->name,
                'surname' => $data->lastName,
                'email' => $data->email,
                'account_type' => $role,
                'password' => $passwords->hash($data->password)
            ]);

            if (!$user instanceof ActiveRow)
            {
                $this->database->rollBack();
                throw new Exception($errorMessage);
            }

            $this->database->commit();
        }
        catch (\Nette\Database\UniqueConstraintViolationException $e)
        {
            $this->database->rollBack();
            throw new Exception('User With This Email Already Exists: ' . $e->getMessage());
        }
    }

    public function registrateUserReturnId(ArrayHash $data, string $role): int
    {
        $errorMessage = 'During the creation of an account, an error occurred.';
        $this->database->beginTransaction();

        if (Role::USER !== $role || Role::ADMIN === $role) {
            throw new Exception($errorMessage);
        }

        try {
            /* Creation Instance of Password For Hash Function */
            $passwords = new Passwords();

            Debugger::log('Trying to insert user with email: ' . $data->email);

            /* Data Insertion - one insert for all data */
            $user = $this->getTable()->insert([
                'name' => $data->name,
                'surname' => $data->lastName,
                'email' => $data->email,
                'account_type' => $role,
                'password' => $passwords->hash($data->password)
            ]);

            if (!$user instanceof ActiveRow) {
                $this->database->rollBack();
                throw new Exception($errorMessage);
            }

            $this->database->commit();

            // Return the ID of the newly created user
            return $user->id;

        } catch (\Nette\Database\UniqueConstraintViolationException $e) {
            $this->database->rollBack();
            throw new Exception('User with this email already exists: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->database->rollBack();
            throw new Exception($errorMessage . ' ' . $e->getMessage());
        }
    }

    public function addAdmin(ArrayHash $data) : void
    {
        try {
            /* Creation Instance of Password For Hash Function */
            $passwords = new Passwords();

            $user = $this->getTable()->insert([
                'account_type' => Role::ADMIN,
                'email' => $data->email,
                'password' => $passwords->hash($data->password),
            ]);
        }
        catch (\Nette\Database\UniqueConstraintViolationException $e)
        {
            throw new Exception('User With This Email Already Exists.');
        }

        if (!$user instanceof ActiveRow)
        {
            throw new Exception('Admin Could Not Be Created.');
        }
    }

    public function getUserDataAsArray(int $userId): ?array
    {
        $user = $this->getUserById($userId);
        if ($user) {
            return $user->toArray();
        }
        return null;
    }

    public function editUser(ArrayHash $data) : void
    {
        $this->database->beginTransaction();

        $this->getClientTable()->wherePrimary($data->clientId)->update([
            'name' => $data->firstName,
            'surname' => $data->lastName,
            'email' => $data->email,
        ]);
        $this->updateUser($data, Role::USER);

        $this->database->commit();
    }

    public function editUserById(int $userId, array $data): void
    {
        $user = $this->getUserById($userId);
        if (!$user instanceof ActiveRow) {
            throw new \Exception("User not found");
        }

        $user->update($data);
    }

    public function editAdmin(ArrayHash $data) : void
    {
        try
        {
            $updateData = [
                'email' => $data->email,
            ];
            if ($data->password)
            {
                $passwords = new Passwords();
                $updateData['password'] = $passwords->hash($data->password);
            }
            $this->getTable()->wherePrimary($data->id)->update($updateData);
        }
        catch (\Nette\Database\UniqueConstraintViolationException $e)
        {
            throw new Exception('User With This Email Already Exists.');
        }
    }

    public function getAllUsers(): Selection
    {
        return $this->getUserTable();
    }

}