<?php
namespace Kozo\Web\Router;

/**
 * Route
 *
 * @author Jason Lam <jasonlam604@gmail.com>
 * @copyright 2016 Jason Lam
 * @package /Kozo/Web/Router
 * @license https://github.com/getkozo/Common/blob/master/LICENSE (MIT License)
 */
class Router
{

    /**
     * @var string $path
     */
    private $path;

    /**
     * @var array $routes;
     */
    private $routes;

    /**
     * Router
     */
    public function __construct()
    {
        $this->path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER['PATH_INFO']) : "/";

        $this->routes = array();
    }

    /**
     * Add route
     *
     * @param Route $route
     *
     * @throws \Exception Thrown when route already exists
     *
     * @return void
     */
    public function addRoute(Route $route)
    {
        if (array_key_exists($route->getRouteId(), $this->routes)) {
            throw new \Exception("Route already exists");
        }

        $this->routes[$route->getRouteId()] = $route;
    }

    /**
     * Remove route
     *
     * @param Route $route
     *
     * @throws \Exception Thrown when route does not exist
     *
     * @return void
     */
    public function removeRoute(Route $route)
    {
        if (array_key_exists($route->getRouteId(), $this->routes)) {
            unset($this->routes[$route->getRouteId()]);
        } else {
            throw new \Exception("Route does not exists");
        }
    }

    /**
     * Determine which route is being called by matching PATH url with defined patterns
     * in each of the route, when match is found then run the route.  Exception is
     * thrown if no route is matched.
     *
     * @throws \Exception
     */
    public function run()
    {
        $isMatch = false;

        if (isset($this->routes) && ! empty($this->routes)) {
            foreach ($this->routes as $route) {
                if ($route->run($this->path)) {
                    $isMatch = true;
                    break;
                }
            }
        }

        if (! $isMatch) {
            throw new \Exception("Unable to match route");
        }
    }

}