<?php
namespace App\MailingService;

use App\Entity\Order;

class MailBodyBuilder
{
    public function __construct()
    {
    }

    public function GenerateOrderEmail(Order $order, $paymentMethod, $baseURL, $message)
    {
        $orderType = ($order instanceof RankBoostOrder) ? 'Rank Boost' : 'Per Win Boost';
        $startRankType = ($orderType == 'Rank Boost') ? 'Start Rank' : 'Rank';
        $desiredRankType = ($orderType == 'Rank Boost') ? 'Desired Rank' : 'Wins';

        $startRankName = json_decode(file_get_contents('../assets/ranks.json'), true)['Ranks'][$order->startRank];

        $desiredRankName = ($orderType == 'Rank Boost') ? json_decode(file_get_contents('../assets/ranks.json'), true)['Ranks'][$order->desiredRank] : $order->desiredRank;
        $duo = $order->duo ? 'Yes' : 'No';

        $confirmationLink = '<a style="color:#0000FF;text-decoration: underline; font-size: 14px; line-height: 16px;" href="' . $baseURL . 'confirmOrder/' . $order->invoiceNumber . '" target="_blank">' . $baseURL . 'confirmOrder/' . $order->invoiceNumber . '</a>';
        $body = file_get_contents('../assets/orderEmailTemplate.html');
        $body = str_replace(array(
            '#orderName#',
            '#invoiceNumber#',
            '#orderDate#',
            '#method#',
            '#price#',
            '#startRankType#',
            '#desiredRankType#',
            '#startRank#',
            '#desiredRank#',
            '#duo#',
            '#confirmationLink#',
            '#message#'),
            (array(
                $order->orderName,
                $order->invoiceNumber,
                $order->orderDate,
                $paymentMethod,
                $order->price,
                $startRankType,
                $desiredRankType,
                $startRankName,
                $desiredRankName,
                $duo,
                $confirmationLink,
                $message
            ))
            , $body);
        return $body;
    }

    public function GenerateCustomerQuestionEmail($name, $messageFromCustomer)
    {
        $body = file_get_contents('../assets/questionEmailTemplate.html');
        $body = str_replace(array(
            '#name#',
            '#customerMessage#'),
            array(
                $name,
                $messageFromCustomer),
            $body);
        return $body;
    }
}