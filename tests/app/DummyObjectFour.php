<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * DummyObjectFour, used for unit testing
 */
class DummyObjectFour implements KozoRouterInterface
{

    public function __construct()
    {}

    public function testMethod()
    {
        print("DummyObjectFour testMethod invoked");
    }

    public function setRequestParameters($data)
    {

    }
}