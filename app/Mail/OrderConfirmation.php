<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $subject = 'Order Confirmation';
        
        $content = '
        <h1>thank You for purchase </h1>
        <h1>Your order will ship soon</h1>
        
        ';
        
        return $this->subject($subject)
        ->html($content);
    }
}
