<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 20:58
 */

namespace App\Entity;

/**
 * Class Customer
 * @package App\Entity
 * @property $customerEmail
 */
class Customer
{
    private $customerEmail;

    public function __construct($customerEmail)
    {
        $this->customerEmail = $customerEmail;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}