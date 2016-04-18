<?php
namespace Kozo\Web\Router;

use Kozo\Web\Router\KozoRouterInterface;

/**
 * Route
 *
 * @author Jason Lam <jasonlam604@gmail.com>
 * @copyright 2016 Jason Lam
 * @package /Kozo/Web/Router
 * @license https://github.com/getkozo/Common/blob/master/LICENSE (MIT License)
 */
class Route
{

    public static $RESPONSE_APPROACH_GLOBAL_ENV = 0;

    public static $RESPONSE_APPROACH_KOZO_INTERFACE = 1;

    public static $RESPONSE_APPROACH_PSR_HTTP_MESSAGE = 2;

    private static $DEFAULT_BASE_PATH = '/';

    /**
     *
     * @var $responseApproach
     */
    private $responseApproach;

    /**
     * Unique Id for Route
     *
     * @var $routeId
     */
    private $routeId;

    /**
     * Http Methods ie: GET, POST, PUT, PATCH, DELETE .
     *
     *
     *
     *
     * ..etc
     *
     * @var $method
     */
    private $httpMethods;

    /**
     * Save the path without any modifications
     *
     * @var $pathOrig
     */
    private $pathOrig;

    /**
     * Url Path for the Route
     *
     * @var $path
     */
    private $path;

    /**
     * Path Part Count, each part is delimited by a slash
     *
     * @var $pathCount;
     */
    private $pathCount;

    /**
     *
     * @var $invokeClass;
     */
    private $invokeClass;

    /**
     *
     * @var $invokeMethod;
     */
    private $invokeMethod;

    /**
     * Retain the number of parameters, namely the :text or :number
     *
     * @var unknown
     */
    private $paramCount;

    /**
     * Flag indicating if the url path contains variables/parameters
     *
     * @var $hasParameters
     */
    private $hasParameters;

    /**
     * Holds the Regex representation of the url
     *
     * @var unknown
     */
    private $pattern;

    /**
     * Indicator if defined path matches requestURL
     *
     * @var $isPathMatch
     */
    private $isPathMatch;

    /**
     *
     * @var $requestPath
     */
    private $requestPath;

    /**
     * Create new Route
     *
     * @param array $httpMethods
     *            Unique identifer
     * @param string $urlPath
     *            URL path and/pattern for the route
     * @param string $invokeClass
     *            Class to instantiate, expects full namespeace ie: MyApp\DummyClass
     * @param string $invokeMethod
     *            Optional. The method to call on the $invokeClass, if not indiciated no method is invoked
     */
    public function __construct($httpMethods, $urlPattern, $invokeClass, $invokeMethod = null)
    {
        if (empty($httpMethods) || ! is_array($httpMethods))
            throw new \InvalidArgumentException("One ore more Http methods required");

        if (empty($urlPattern))
            throw new \InvalidArgumentException("Missing or empty urlPattern");

        $this->responseApproach = self::$RESPONSE_APPROACH_GLOBAL_ENV;

        $this->isPathMatch = false;

        $this->pathOrig = $urlPattern;

        $this->routeId = $this->generateId();
        $this->httpMethods = $httpMethods;
        $this->parsePath($urlPattern);
        $this->invokeClass = $invokeClass;
        $this->invokeMethod = $invokeMethod;
    }

    /**
     * Simple random string generate to used to id the route
     *
     * @return string Generated random string
     */
    private function generateId()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
    }

    /**
     * Determine the url pattern for regex use and number of parameteres
     *
     * @param string $path
     *            URL path
     *
     * @return void
     */
    private function parsePath($path)
    {

        // Set Defaults
        $this->path = self::$DEFAULT_BASE_PATH;
        $this->pathCount = 0;

        if (isset($path) && strlen(trim($path)) > 0 && strcmp(trim($path), '/') != 0) {

            $this->path = $this->normalizePath($path);
            $this->pathCount = $this->getPathCount($this->path);
            // $this->path = '/' . $this->path;

            // Build Pattern for Pattern Matching
            if (stristr($this->path, ':') === FALSE) {
                $this->hasParameters = false;
            } else {
                $this->hasParameters = true;
                $path = $this->path;
                $this->pattern = str_replace(':number', '(\d+)', $path);
                $this->pattern = str_replace(':text', '(\w+)', $this->pattern);
                $this->pattern = str_replace('/', '\/', $this->pattern);
                $this->pattern = rtrim($this->pattern, '/') . '/';
                $this->pattern = '/' . ltrim($this->pattern, '/');
            }
        }
    }

    /**
     * Determine number of parts that make up the given path where each part
     * is delimited by forward slash
     *
     * @param string $path
     *            URL path portion
     *
     * @return int total number of parts in a path
     */
    private function getPathCount($path)
    {
        if (strcmp($path, "/") == 0) {
            return 0;
        } else {
            return count(explode("/", $path));
        }
    }

    /**
     * Cleanup and baseline path, remove trailing slashes and whitespace
     *
     * @param string $path
     *
     * @param
     *            string Normalized URL path
     */
    private function normalizePath($path)
    {
        if (strcmp($path, "/") == 0) {
            $path = "/";
        } else {
            $path = strtolower(trim($path));
            $path = rtrim($path, '/');
            $path = ltrim($path, '/');
            $path = '/' . $path;
        }

        return $path;
    }

    /**
     * Determine the URL path without the Query string data
     *
     * @return string URL path without any query string data
     */
    private function getPathWithoutQueryData()
    {
        if (stristr($this->path, '?') === FALSE) {
            return $this->path;
        } else {
            return stristr($this->path, '?', true);
        }
    }

    /**
     * Parse out the parameters based on defined pattern
     *
     * @param string $requestPath
     */
    private function parseParameters($requestPath)
    {
        $parameters = array();

        if ($this->hasParameters) {
            $requestPathParts = explode('/', $requestPath);

            foreach (explode('/', $this->pathOrig) as $key => $parameter) {
                if (stristr($parameter, ':')) {
                    $parameters[] = $requestPathParts[$key];
                }
            }
        }

        return $parameters;
    }

    /**
     * Determine if the definted path (pattern) for the route matches the given request URL path
     *
     * @param string $requestPath
     *            Request URL Path
     *
     * @return boolean true if match, false if no match
     */
    private function isMatch($requestPath)
    {
        $result = false;

        $this->requestPath = $requestPath;

        if (in_array($_SERVER['REQUEST_METHOD'], $this->httpMethods)) {

            $requestPath = $this->normalizePath($requestPath);

            // Check if path route count is equal to given path count of request path if not
            // immediately then this is not a match
            if ($this->pathCount == $this->getPathCount($requestPath)) {

                // Check route have any parameters
                if ($this->hasParameters) {

                    // Regex match against pattern
                    if (preg_match($this->pattern, $requestPath)) {
                        $result = true;
                    }
                } else {

                    // No pattern then a simple string match will do
                    if (strcmp($this->getPathWithoutQueryData(), $requestPath) == 0) {
                        $result = true;
                    }
                }
            }
        }

        $this->isPathMatch = $result;

        return $result;
    }

    public function setAccessApproach($responseApproach)
    {
        if ($responseApproach == self::$RESPONSE_APPROACH_GLOBAL_ENV || $responseApproach == self::$RESPONSE_APPROACH_KOZO_INTERFACE || $responseApproach == self::$RESPONSE_APPROACH_PSR_HTTP_MESSAGE) {
            $this->responseApproach = $responseApproach;
        } else {
            throw new \Exception("Invalid response approach");
        }
    }

    /**
     * Return Route ID
     *
     * @return string Route Unique ID
     */
    public function getRouteId()
    {
        return $this->routeId;
    }

    /**
     * Runs the Callback class and method if indicated, a little pointless used on its own
     * the intent to be invoked by the Router
     *
     * @param
     *            Request URL ,url the entered into the browser or the endpoint called via a API client, just the URL does not include hostname portion ie: /user/4850/profile
     *
     * @return boolean Return true if successful run else false
     */
    public function run($requestPath)
    {
        $requestPath = ($requestPath == null) ? "/" : $requestPath;

        if ($this->isMatch($requestPath)) {

            // Invoke Class which of course invoke the constructor
            $object = new $this->invokeClass();

            if($this->responseApproach == self::$RESPONSE_APPROACH_KOZO_INTERFACE) {

                if($object instanceof KozoRouterInterface) {
                    $object->setRequestParameters($this->parseParameters($requestPath));
                } else {
                    return false;
                }

            } else {
                $_ENV["KOZO_PARAMETERS"] = $this->parseParameters($requestPath);
            }

            // If method is give then invoke the indicated method on the object
            if ($this->invokeMethod != null) {
                call_user_func(array(
                    $object,
                    $this->invokeMethod
                ));
            }

            return true;
        } else {
            return false;
        }
    }
}