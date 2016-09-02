<?php

/**
 * Created by PhpStorm.
 * User: rus
 * Date: 05.08.16
 * Time: 20:31
 */
class AuthorizationClass
{
    private $login;
    private $password;

    public function __construct($login, $password)
    {
        $this->login    = $login;
        $this->password = $password;
    }

    public function checkLogin()
    {
        return $this->login != $this->password;
    }
}