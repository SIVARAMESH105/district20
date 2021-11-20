<?php
namespace App\Listeners;
 
use App\Events\ContactFormEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ContactFormMail;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\User;
use Mail;
use App\Models\Users;

/**
 * Class ContactFormMailListener
 * namespace App\Listeners
 * @package App\Events\ContactFormEvent
 * @package Illuminate\Queue\InteractsWithQueue
 * @package Illuminate\Contracts\Queue\ShouldQueue
 * @package App\Mail\ContactFormMail
 * @package Illuminate\Auth\Passwords\PasswordBroker
 * @package App\User
 * @package Mail
 */
class ContactFormMailListener
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn $event
     * @return void
     */
    public function handle(ContactFormEvent $event)
    {
        $info = $event->info;
        $chapter_id = $info['chapter_id'];
        $mails = (new Users)->getChapterAdminEmails($chapter_id);        
        if(env("CONTACT_FORM_ADMIN_MAIL"))
            $mails[] = env("CONTACT_FORM_ADMIN_MAIL");
        $mails[] = "clvinoth@gmail.com";
        $subject = "District10 - New Contact Form Submitted";
        Mail::to($mails)->send(new ContactFormMail($subject, $info));        
    }
}