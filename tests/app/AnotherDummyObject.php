<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * AnotherDummyObject, used for unit testing
 */
class AnotherDummyObject implements KozoRouterInterface
{

    public function __construct()
    {
        // Does nothing
    }

    public function testMethod()
    {
        print("testMethod invoked");
    }

    public function setRequestParameters($data)
    {

    }
}