<?php
use Kozo\Web\Router\Route;
use Kozo\Web\Router\Router;

/**
 * Router Unit Tests
 */
class RouterTest extends PHPUnit_Framework_TestCase
{

    public function testAddRoute()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route1 = new Route(["GET"], "/three", "App\DummyObjectThree","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route2 = new Route(["GET"], "/four", "App\DummyObjectFour","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route3 = new Route(["GET"], "/five", "App\DummyObjectFive","testMethod");

        // Fake out Path to /four
        $_SERVER["PATH_INFO"] = "/four";

        $router = new Router();
        $router->addRoute($route1);
        $router->addRoute($route2);
        $router->addRoute($route3);

        $this->expectOutputString("DummyObjectFour testMethod invoked");

        $router->run();
    }

    public function testAddValidPathOverride() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route1 = new Route(["GET"], "/three", "App\DummyObjectThree","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route2 = new Route(["GET"], "/four", "App\DummyObjectFour","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route3 = new Route(["GET"], "/five", "App\DummyObjectFive","testMethod");

        // Fake out Path to /three
        $_SERVER["PATH_INFO"] = "/three";

        $router = new Router();
        $router->addRoute($route1);
        $router->addRoute($route2);
        $router->addRoute($route3);

        $router->overridePath("/four");

        $this->expectOutputString("DummyObjectFour testMethod invoked");

        $router->run();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInValidPathOverride() {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route1 = new Route(["GET"], "/three", "App\DummyObjectThree","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route2 = new Route(["GET"], "/four", "App\DummyObjectFour","testMethod");

        $_SERVER["REQUEST_METHOD"] = "GET";
        $route3 = new Route(["GET"], "/five", "App\DummyObjectFive","testMethod");

        // Fake out Path to /three
        $_SERVER["PATH_INFO"] = "/three";

        $router = new Router();
        $router->addRoute($route1);
        $router->addRoute($route2);
        $router->addRoute($route3);

        $router->overridePath("");

        $this->expectOutputString("DummyObjectFour testMethod invoked");

        $router->run();
    }
}