<?php

declare(strict_types=1);

namespace App\UserModule\Model;

use Nette\Security\Permission;
use Nette\Security\Authorizator;
use Nette\Utils\Finder;
use Nette\InvalidArgumentException;

final class AuthenticationFactory
{
    use \Nette\StaticClass;

    public const ROLE_ADMIN = 'admn';
    public const ROLE_USER = 'user';

    public const ACTION_ADD = 'add';
    public const ACTION_EDIT = 'edit';
    public const ACTION_LIST = 'list';
    public const ACTION_DELETE = 'delete';

    /**
     * Creates an Authorizator instance and sets up roles, resources, and rules.
     *
     * @throws InvalidArgumentException
     */
    public static function create(array $roles, string $appDir) : Authorizator
    {
        $permission = new Permission();
        self::setupRoles($permission);
        self::setupResources($permission, $appDir);
        self::setupRules($permission, $roles);

        return $permission;
    }

    /**
     * Sets up the roles and their hierarchy.
     *
     * @throws InvalidArgumentException
     */
    private static function setupRoles(Permission $permission): void
    {
        $permission->addRole(self::ROLE_USER);
        $permission->addRole(self::ROLE_ADMIN, self::ROLE_USER);
    }

    /**
     * Sets up resources by scanning the application directory for modules and presenters.
     *
     * @throws InvalidArgumentException
     */
    private static function setupResources(Permission $permission, string $appDir): void
    {
        // Adding a Special Placeholder Resource '*'
        $permission->addResource('*');
        $permission->addResource('usermodule.user');

        // The Rest of The Code For Adding Other Resources
        foreach (Finder::findDirectories('*Module')->in($appDir) as $dir)
        {
            preg_match('~^(.+)Module\z~', $dir->getFilename(), $matches);
            $moduleName = strtolower($matches[1]);

            $presentersDir = $dir->getRealPath() . DIRECTORY_SEPARATOR . 'Presenters';
            if (is_dir($presentersDir)) {
                foreach (Finder::findFiles()->in($presentersDir) as $presenter) {
                    preg_match('~^(.+)Presenter.php\z~', $presenter->getFilename(), $matches);
                    $presenterName = strtolower($matches[1]);

                    $permission->addResource("$moduleName.$presenterName");
                }
            }
        }
    }

    /**
     * Sets up access rules for the defined roles.
     *
     * @throws InvalidArgumentException
     */
    private static function setupRules(Permission $permission, array $roles): void
    {
        foreach ($roles as $role => $roleData) {
            if (!$permission->hasRole($role)) {
                $permission->addRole($role, $roleData['parent'] ?? null);
            }

            if (!empty($roleData['resources']) && is_array($roleData['resources'])) {
                foreach ($roleData['resources'] as $resource => $actions) {
                    if ($permission->hasResource($resource)) {
                        $permission->allow($role, $resource, $actions ?: '*');
                    }
                }
            } else {
                $permission->allow($role, '*', '*');
            }

            if (!empty($roleData['denyResources']) && is_array($roleData['denyResources'])) {
                foreach ($roleData['denyResources'] as $resource => $actions) {
                    if ($permission->hasResource($resource)) {
                        $permission->deny($role, $resource, $actions ?: '*');
                    }
                }
            } else {
                $permission->deny($role, '*', '*');
            }
        }
    }
}