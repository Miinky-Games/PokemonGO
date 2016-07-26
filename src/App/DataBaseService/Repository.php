<?php
/**
 * Created by PhpStorm.
 * User: sebas
 * Date: 26.07.2016
 * Time: 19:36
 */

namespace App\DataBaseService;

use App\Entity\Order;
use PDO;
use Exception;

class Repository
{
    private $pdo;
    const logFile ='../logs/Repository.log';

    public function __construct(PDO $pdo)
    {
        $this->pdo=$pdo;
    }

    private function LogToFile($logText, Exception $exp=null){
        $log = '['.date("Y/m/d H:i:s").'] ';
        if($exp != null){
            $log.=" Err code: ".$exp->getCode()." Message: ".$exp->getMessage();
        }
        $log.=$logText.PHP_EOL;
        file_put_contents(self::logFile,$log,FILE_APPEND);
    }
}