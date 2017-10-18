<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * DummyObject, used for unit testing
 *
 */
class DummyObject implements KozoRouterInterface
{

    public function __construct()
    {
        // For unit testing, dummy output as so we know the constructor was invoked
       // print("DummyObject constructor invoked");
    }

    public function setRequestParameters($data)
    {

    }

}