<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * DummyObjectTwo, used for unit testing
 *
 */
class DummyObjectTwo implements KozoRouterInterface
{

    public function __construct()
    {
        // For unit testing, dummy output as so we know the constructor was invoked
       print("DummyObjectTwo constructor invoked");
    }

    public function setRequestParameters($data)
    {

    }
}