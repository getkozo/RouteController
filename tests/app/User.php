<?php
namespace App;

/**
 * User, used for unit testing
 *
 */
class User
{

    public function __construct()
    {
    }

    public function listUsers() {
        print 'list of users';
    }

    public function createUser() {
        print 'create user';
    }

    public function updateUser() {
        print 'update user ' . $_ENV["KOZO_PARAMETERS"][0];
    }

}