<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 11:00
 */

namespace Model;

class Admin
{
    private $user;
    private $host;
    private $password_hash;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getPasswordHash()
    {
        return $this->password_hash;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }
}