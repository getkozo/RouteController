<?php
use Kozo\Web\Router\Route;

/**
 * Route Unit Tests
 */
class RouteTest extends PHPUnit_Framework_TestCase
{

    /**
     * Just a basic unique test
     */
    public function testRouteIdUnique()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        for ($i = 0; $i < 10000; $i ++) {

            $this->assertNotEquals((new Route([
                "GET"
            ], "/", null, null))->getRouteId(), (new Route([
                "GET"
            ], "/", 'App\DummyObject'))->getRouteId());
        }
    }

    public function testMethodsExist()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route = new Route([
            "GET"
        ], "/", 'App\DummyObject');
        $this->assertEquals($route->execute("/"), true);

        $_SERVER["REQUEST_METHOD"] = "POST";
        $route = new Route([
            "GET",
            "POST"
        ], "/", 'App\DummyObject');
        $this->assertEquals($route->execute("/"), true);

        $_SERVER["REQUEST_METHOD"] = "PUT";
        $route = new Route([
            "GET",
            "POST",
            "PUT",
            "DELETE"
        ], "/", 'App\DummyObject');
        $this->assertEquals($route->execute("/"), true);

        $_SERVER["REQUEST_METHOD"] = "DELETE";
        $route = new Route([
            "GET",
            "POST",
            "PUT",
            "DELETE"
        ], "/", 'App\DummyObject');
        $this->assertEquals($route->execute("/"), true);
    }

    public function testMethoNotExist()
    {
        $_SERVER["REQUEST_METHOD"] = "PATCH";
        $route = new Route([
            "GET",
            "POST",
            "PUT",
            "DELETE"
        ], "/", 'App\DummyObject');
        $this->assertEquals($route->execute("/"), false);
    }

    public function testClassCallback()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route = new Route([
            "GET"
        ], "/", "App\DummyObjectTwo");

        $this->assertEquals($route->execute("/"), true);
        $this->expectOutputString("DummyObjectTwo constructor invoked");
    }

    public function testMethodCallback()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $route = new Route([
            "GET",
            "POST",
            "PUT",
            "DELETE"
        ], "/", "App\AnotherDummyObject", "testMethod");
        $this->assertEquals($route->execute("/"), true);
        $this->expectOutputString("testMethod invoked");
    }

    public function testMultipleMethods()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $defaultRoute = new Route([
            "GET",
            "POST",
            "PUT",
            "DELETE",
            "PATCH"
        ], "/", 'App\DummyObject');
        $this->assertEquals($defaultRoute->execute("/"), true);
    }

    /**
     * Using endpoint /users with METHOD GET
     */
    public function testFakeUserList()
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $routeUsers = new Route([
            "GET"
        ], "/users", 'App\User', 'listUsers');
        $this->assertEquals($routeUsers->execute("/users"), true);
        $this->expectOutputString("list of users");
    }

    /**
     * Using endpoint /users with METHOD POST
     */
    public function testFakeUserCreate()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $routeUsers = new Route([
            "POST"
        ], "/users", 'App\User', 'createUser');
        $this->assertEquals($routeUsers->execute("/users"), true);
        $this->expectOutputString("create user");
    }

    public function testFakeUserUpdate()
    {
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $routeUsers = new Route([
            "PUT"
        ], "/users/:number", 'App\User', 'updateUser');
        $this->assertEquals($routeUsers->execute("/users/48593"), true);
        $this->expectOutputString("update user 48593");
    }
}