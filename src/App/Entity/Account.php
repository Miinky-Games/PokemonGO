<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 21:01
 */

namespace App\Entity;
/**
 * Class Account
 * @package App\Entity
 * @property $accountName
 * @property $accountPassword
 */
class Account
{
    private $accountName;
    private $accountPassword;

    public function __construct($accountName, $accountPassword)
    {
        $this->accountName = $accountName;
        $this->accountPassword = $accountPassword;
    }

    public function __get($name)
    {
        return $this->$name;
    }

}