<?php
namespace App\MailingService;

use Exception;

class MailLogger
{
    const logFile = '../logs/MailingService.log';

    public function __construct()
    {
    }

    public function LogToFile($logText, Exception $exp = null)
    {
        $log = '[' . date("Y/m/d H:i:s") . '] ';
        if ($exp != null) {
            $log .= " Err code: " . $exp->getCode() . " Message: " . $exp->getMessage();
        }
        $log .= $logText . PHP_EOL;
        file_put_contents(self::logFile, $log, FILE_APPEND);
    }
}