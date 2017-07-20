<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList();
        $router[] = self::createBrokerRouter();
        $router[] = self::createAdminRouter();
        $router[] = self::createFrontRouter();
        return $router;
    }

    private static function createFrontRouter(){
        $router = new RouteList('Front');
        $router[] = new Route('/[index.html]', 'Homepage:default');
        $router[] = new Route('<action>.html', 'Homepage:default');
        $router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
    }

    private static function createAdminRouter(){
        $router = new RouteList('Admin');
        $router[] = new Route('admin/<presenter>/<action>[/<id>]', 'Email:default');
        return $router;
    }

    private static function createBrokerRouter()
    {
        $router = new RouteList('Broker');
        $router[] = new Route('broker/<presenter>/<action>[/<id>]', 'Dashboard:default');
        return $router;
    }
}
