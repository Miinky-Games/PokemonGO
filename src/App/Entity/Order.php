<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 19:36
 */

namespace App\Entity;

/**
 * Class Order
 * @package App\Entity
 * @property $price
 * @property $invoiceNumber
 * @property $orderDate
 * @property $customerID
 * @property $accountID
 * @property $boostingStart
 * @property $boostingEnd
 * @property $finished
 */
class Order
{
    private $invoiceNumber;
    private $price;
    private $orderDate;
    private $customerID;
    private $accountID;
    private $boostingStart;
    private $boostingEnd;
    private $finished;

    public function __construct()
    {
        $this->invoiceNumber = InvoiceNumber::Generate();
        $this->orderDate = date("Y-m-d H:i:s");
    }

    public function __get($name){
        return $this->$name;
    }
}