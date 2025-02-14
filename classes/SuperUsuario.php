<?php

class SuperUsuario
{
    protected $username;

    public function __construct($username)
    {
        $this->$username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
}
