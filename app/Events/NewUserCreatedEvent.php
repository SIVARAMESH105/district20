<?php 
namespace App\Events;
 
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;

/**
 * Class NewUserCreatedEvent
 * namespace App\Events
 * @package App\Events\Event
 * @package Illuminate\Queue\SerializesModels
 * @package Illuminate\Contracts\Broadcasting\ShouldBroadcast
 * @package Illuminate\Http\Request
 */
class NewUserCreatedEvent extends Event
{
    use SerializesModels;
 
    public $user;
 
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request, $info)
    {
        $this->request = $request;
        $this->info = $info;
    }
 
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
    /**
     * This method is used to get event response
     *
     */
    public function fire($event, $payload = [], $halt = false)
    {       
        $responses = [];
        foreach ($this->getListeners($event) as $listener) {
            $response = call_user_func_array($listener, $payload);
            $responses[] = $response;
        }
        return $halt ? null : $responses;
    }
}
