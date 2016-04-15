<?php
namespace Kozo\Web\Router;

/**
 * KozoRouterInterface
 *
 * @author Jason Lam <jasonlam604@gmail.com>
 * @copyright 2016 Jason Lam
 * @package /Kozo/Web/Route
 * @license https://github.com/getkozo/Common/blob/master/LICENSE (MIT License)
 */
interface KozoRouterInterface
{
    /**
     * To be used by a Route to store request parameter url data
     *
     * For example if the route defined as /users/:number/profile
     * and is actual path is /users/38459/profile
     *
     * $data would contain an array structure:
     *
     * Array
     * {
     *   0 =>  38459
     * }
     *
     */
    public function setRequestParameters($data);
}