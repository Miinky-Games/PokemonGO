<?php
namespace App\MailingService;

use Swift_Message;

class MailBuilder
{
    public function __construct()
    {
    }

    /**
     * @param $title
     * @param $from
     * @param $to
     * @param MailBodyBuilder $body
     * @param array $replayTo
     * @return mixed
     */
    public function newMail($title, $from, $to, $body, $replayTo = null)
    {
        $mail = Swift_Message::newInstance()
            ->setSubject($title)
            ->setReplyTo($replayTo)
            ->setFrom($from)
            ->setTo($to)
            ->addPart($body, 'text/html');
        return $mail;
    }
}
