<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * DummyObjectThree, used for unit testing
 */
class DummyObjectThree implements KozoRouterInterface
{

    public function __construct()
    {}

    public function testMethod()
    {
        print("DummyObjectThree testMethod invoked");
    }

    public function setRequestParameters($data)
    {

    }
}