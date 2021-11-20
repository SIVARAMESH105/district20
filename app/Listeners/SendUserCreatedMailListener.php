<?php
namespace App\Listeners;
 
use App\Events\NewUserCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewUserCreatedEventMail;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\User;
use Mail;

/**
 * Class SendUserCreatedMailListener
 * namespace App\Listeners
 * @package App\Events\NewUserCreatedEvent
 * @package Illuminate\Queue\InteractsWithQueue
 * @package Illuminate\Contracts\Queue\ShouldQueue
 * @package App\Mail\NewUserCreatedEventMail
 * @package Illuminate\Auth\Passwords\PasswordBroker
 * @package App\User
 * @package Mail
 */
class SendUserCreatedMailListener
{
    /**
     * Handle the event.
     *
     * @param  UserLoggedIn $event
     * @return void
     */
    public function handle(NewUserCreatedEvent $event)
    {
        $info = $event->info;
        $request = $event->request;
        $mail = $info['email'];
        $mails = array($mail);
        $subject = "User Created Successfully";
        $token = app(PasswordBroker::class)->createToken(User::where('email', $mail)->first());
        $info['tokenUrl'] = url('/password/reset/'.$token.'?email='.$mail);
        Mail::to($mails)->send(new NewUserCreatedEventMail($subject, $info));        
    }
}