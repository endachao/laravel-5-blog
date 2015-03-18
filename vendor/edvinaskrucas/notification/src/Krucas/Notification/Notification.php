<?php namespace Krucas\Notification;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;

class Notification
{
    /**
     * Default container name.
     *
     * @var string
     */
    protected $defaultContainer;

    /**
     * List of instantiated containers.
     *
     * @var array
     */
    protected $containers = array();

    /**
     * Default types for new containers.
     *
     * @var array
     */
    protected $types = array();

    /**
     * Default format for new containers.
     *
     * @var array
     */
    protected $format = array();

    /**
     * Default formats for new containers.
     *
     * @var array
     */
    protected $formats = array();

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Create new instance.
     *
     * @param string $container
     * @param array $types
     * @param array $format
     * @param array $formats
     */
    public function __construct($container = 'default', $types = array(), $format = array(), $formats = array())
    {
        $this->defaultContainer = $container;
        $this->types = $types;
        $this->format = $format;
        $this->formats = $formats;
    }

    /**
     * Return name of default container.
     *
     * @return string
     */
    public function getDefaultContainerName()
    {
        return $this->defaultContainer;
    }

    /**
     * Set types for a container.
     *
     * @param $container
     * @param array $types
     * @return \Krucas\Notification\Notification
     */
    public function setContainerTypes($container, $types = array())
    {
        $this->types[$container] = $types;

        return $this;
    }

    /**
     * Return types for a container.
     *
     * @param $container
     * @return array
     */
    public function getContainerTypes($container)
    {
        if (isset($this->types[$container])) {
            return $this->types[$container];
        }

        return array();
    }

    /**
     * Set format for a container.
     *
     * @param $container
     * @param null $format
     * @return \Krucas\Notification\Notification
     */
    public function setContainerFormat($container, $format = null)
    {
        $this->format[$container] = $format;

        return $this;
    }

    /**
     * Return format for a container.
     *
     * @param $container
     * @return string|null
     */
    public function getContainerFormat($container)
    {
        if (isset($this->format[$container])) {
            return $this->format[$container];
        }

        return null;
    }

    /**
     * Set formats for a container.
     *
     * @param $container
     * @param array $formats
     * @return \Krucas\Notification\Notification
     */
    public function setContainerFormats($container, $formats = array())
    {
        $this->formats[$container] = $formats;

        return $this;
    }

    /**
     * Return formats for a container.
     *
     * @param $container
     * @return array
     */
    public function getContainerFormats($container)
    {
        if (isset($this->formats[$container])) {
            return $this->formats[$container];
        }

        return array();
    }

    /**
     * Add new container.
     *
     * @param $container
     * @param array $types
     * @param null $defaultFormat
     * @param array $formats
     * @return \Krucas\Notification\Notification
     */
    public function addContainer($container, $types = array(), $defaultFormat = null, $formats = array())
    {
        if (isset($this->containers[$container])) {
            return $this;
        }

        $this->containers[$container] = new NotificationsBag($container, $types, $defaultFormat, $formats);
        $this->containers[$container]->setNotification($this);

        return $this;
    }

    /**
     * Return array of available containers.
     *
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * Returns container instance.
     *
     * @param null $container
     * @param callable $callback
     * @return mixed
     */
    public function container($container = null, Closure $callback = null)
    {
        $container = is_null($container) ? $this->defaultContainer : $container;

        if (!isset($this->containers[$container])) {
            $this->addContainer(
                $container,
                $this->getContainerTypes($container),
                $this->getContainerFormat($container),
                $this->getContainerFormats($container)
            );
        }

        if (is_callable($callback)) {
            $callback($this->containers[$container]);
        }

        return $this->containers[$container];
    }

    /**
     * Create new message instance.
     *
     * @param null $message
     * @return \Krucas\Notification\Message
     */
    public function message($message = null)
    {
        $m = new Message();
        $m->setMessage($message);
        return $m;
    }

    /**
     * Fire given event.
     *
     * @param $event
     * @param \Krucas\Notification\NotificationsBag $notificationBag
     * @param \Krucas\Notification\Message $message
     * @return array|bool|null
     */
    public function fire($event, NotificationsBag $notificationBag, Message $message)
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $event = "notification.{$event}: ".$notificationBag->getName();

        return static::$dispatcher->fire($event, array($this, $notificationBag, $message));
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     *
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }

    /**
     * Calls NotificationBag function for a default container.
     *
     * @param $name
     * @param $arguments
     * @return \Krucas\Notification\NotificationsBag|null
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->container(null), $name), $arguments);
    }
}
