<?php

error_reporting(E_ALL);

use Phalcon\Loader;
use Phalcon\Mvc\Router;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;
use Phalcon\Escaper;


require_once __DIR__ . '/vendor/autoload.php';

class Application extends BaseApplication
{
    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    protected function registerServices()
    {

        $di = new FactoryDefault();
        $di->set(
            'mongo',
            function () {
                $mongo = new \MongoDB\Client("mongodb://mongo", array("username" => 'root', "password" => "password123"));

                return $mongo->store;
            },
            true
        );

        $loader = new Loader();

        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader
            ->registerDirs([__DIR__ . '/../app/library/'])
            ->register();

        // Registering a router
        $di->set('router', function () {

            $router = new Router();

            $router->setDefaultModule("frontend");

            $router->add('/:controller/:action', [
                'module'     => 'frontend',
                'controller' => 1,
                'action'     => 2,
            ])->setName('frontend');

            $router->add("/admin/login", [
                'module'     => 'admin',
                'controller' => 'login',
                'action'     => 'index',
            ])->setName('admin-login');

            $router->add("/admin/products/:action", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 1,
            ])->setName('admin-product');

            $router->add("/admin/products/addProduct", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 'addProduct',
            ])->setName('admin-addProduct');

            $router->add("/admin/products-dashboard", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 'dashboard',
            ])->setName('admin-dashboard');

            $router->add("/admin/products/editProduct", [
                'module'     => 'admin',
                'controller' => 'products',
                'action'     => 'editProduct',
            ])->setName('admin-editProduct');

            $router->add("/listProducts", [
                'module'     => 'frontend',
                'controller' => 'products',
                'action'     => 'listProducts',
            ])->setName('admin-editProduct');

            return $router;
        });

        $this->setDI($di);
    }

    public function main()
    {

        $this->registerServices();

        // Register the installed modules
        $this->registerModules([
            'frontend' => [
                'className' => 'Multiple\Frontend\Module',
                'path'      => '../app/frontend/Module.php'
            ],
            'admin'  => [
                'className' => 'Multiple\Admin\Module',
                'path'      => '../app/admin/Module.php'
            ]
        ]);

        $response = $this->handle($_SERVER['REQUEST_URI']);

        $response->send();
    }
}

$application = new Application();
$application->main();
