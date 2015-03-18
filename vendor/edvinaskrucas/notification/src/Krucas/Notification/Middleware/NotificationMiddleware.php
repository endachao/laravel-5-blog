<?php namespace Krucas\Notification\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Session\Store;
use Krucas\Notification\Message;
use Krucas\Notification\Notification;

class NotificationMiddleware implements Middleware
{
    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var \Krucas\Notification\Notification
     */
    protected $notification;

    /**
     * @var string
     */
    protected $sessionPrefix;

    /**
     * @param \Illuminate\Session\Store $session
     * @param \Krucas\Notification\Notification $notification
     * @param string $sessionPrefix
     */
    public function __construct(Store $session, Notification $notification, $sessionPrefix)
    {
        $this->session = $session;
        $this->notification = $notification;
        $this->sessionPrefix = $sessionPrefix;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $containerNames = $this->session->get($this->sessionPrefix.'containers', array());

        $sessionVariables = $this->session->all();

        foreach ($containerNames as $containerName) {
            foreach ($sessionVariables as $sessionKey => $value) {
                if (strpos($sessionKey, $this->sessionPrefix.$containerName) === 0 && is_string($value)) {
                    $jsonMessage = json_decode($value);
                    $this->notification->container($containerName)->add(
                        $jsonMessage->type,
                        new Message(
                            $jsonMessage->type,
                            $jsonMessage->message,
                            false,
                            $jsonMessage->format,
                            $jsonMessage->alias,
                            $jsonMessage->position
                        ),
                        false
                    );
                }
            }
        }

        return $next($request);
    }
}
