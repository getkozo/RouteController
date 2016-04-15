<?php
namespace App;

use Kozo\Web\Router\KozoRouterInterface;

/**
 * Books, used for unit testing
 */
class Books implements KozoRouterInterface
{

    private $data;

    public function __construct()
    {}

    public function updateBook()
    {
        print 'update book ' . $this->data[0];
    }

    public function setRequestParameters($data)
    {
        $this->data = $data;
    }
}