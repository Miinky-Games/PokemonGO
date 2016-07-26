<?php
namespace App\PayPalService;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use App\Entity\Order;

class PayPalHandler
{
    private $payer;
    private $payment;

    public function __construct()
    {
        $this->payer = new Payer();
        $this->payer->setPaymentMethod('paypal');
        $this->payment = new Payment();
    }

    public function CreatePayment(Order $order, $baseURL)
    {
        $item = new Item();
        $item->setName('PokemonGo Boost')
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setSku('0')
            ->setPrice($order->price);

        $itemList = new ItemList();
        $itemList->setItems(array(
            $item
        ));

        $details = new Details();
        $details->setSubtotal($order->price);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($order->price)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Description')
            ->setInvoiceNumber($order->invoiceNumber);

        $redirectURL = new RedirectUrls();
        $redirectURL->setReturnUrl($baseURL . 'payment?success=true')
            ->setCancelUrl($baseURL . 'payment?success=false');

        $this->payment->setIntent('sale')
            ->setPayer($this->payer)
            ->setRedirectUrls($redirectURL)
            ->setTransactions(array(
                $transaction
            ));
    }

    public function GetApprovalUrl($payPalConnection)
    {
        $this->payment->create($payPalConnection);
        $approvalLink = $this->payment->getApprovalLink();

        return $approvalLink;
    }

    public function Execute($payerId, $paymentID, $apiContext)
    {
        $payment = Payment::get($paymentID, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {
            $payment->execute($execution, $apiContext);
            $result = true;
        } catch (\Exception $exp) {
            $result = false;
        }
        return $result;
    }
}