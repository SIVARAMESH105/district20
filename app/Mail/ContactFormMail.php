<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ContactFormMail
 * namespace App\Mail
 * @package Illuminate\Bus\Queueable
 * @package Illuminate\Mail\Mailable
 * @package Illuminate\Queue\SerializesModels
 * @package Illuminate\Contracts\Queue\ShouldQueue
 */
class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $body;
    public $attach = null;
    public $from_mail = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $data)
    {   
        $this->subject = $subject;
        $this->data = $data;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {    
        $this->markdown('mails.new_contact_form_event_mail', $this->data)
        	 ->subject($this->subject);        
        return $this;
    }
}
