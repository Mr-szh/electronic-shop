<?php

namespace App\Listeners;

use Cookie;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Cookie\QueueingFactory;

class SetCookieListener
{
    protected $cookieQueue;
    protected $cookie;

    /**
     * Create the event listener.
     *
     * @param QueueingFactory $cookieQueue
     * @param Cookie $cookie
     * @internal param QueueingFactory $cookie
     */
    public function __construct(QueueingFactory $cookieQueue, Cookie $cookie)
    {
        $this->cookieQueue = $cookieQueue;
        $this->cookie = $cookie;
    }

    /**
     * Handle the event.
     *
     * @param  Login $event
     */
    public function handle(Login $event)
    {
        $cookieName = \Auth::guard()->getRecallerName();
        $min = 3 * 1440;

        if ($event->remember) {
            $value = $this->cookie->get($cookieName);
            $first_time = $this->cookie->get('first_time');
            if ($value && ! $first_time) {
                $this->cookieQueue->queue('first_time', 'changed', $min);
                $this->cookieQueue->queue($cookieName, $value, $min);
            }
        }
    }
}