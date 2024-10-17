<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use Tracy\Debugger;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList;
        $router->addRoute('', 'CommonModule:Home:default');

        $router->addRoute('user/authentication/sign-in', 'UserModule:Authentication:signIn');

        $router[] = new \Nette\Application\Routers\Route('[<module>[/<presenter>[/<action>[/<id \d+>]]]]', [
            'module' => 'CommonModule',
            'presenter' => 'Home',
            'action' => 'default',
        ]);

        return $router;
    }
}
