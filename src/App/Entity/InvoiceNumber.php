<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 19:37
 */

namespace App\Entity;


class InvoiceNumber
{
    public static function Generate(){
        return date("Y/mdHis");
    }
}