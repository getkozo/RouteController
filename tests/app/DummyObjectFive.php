<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;
/**
 * DummyObjectFive, used for unit testing
 */
class DummyObjectFive implements KozoRouterInterface
{

    public function __construct()
    {}

    public function testMethod()
    {
        print("DummyObjectFive testMethod invoked");
    }

    public function setRequestParameters($data)
    {

    }
}