<?php namespace Krucas\Notification\Facades;

use Illuminate\Support\Facades\Facade;

class Notification extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return 'notification';
    }
}
