<?php namespace Krucas\Notification;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class NotificationsBag implements Arrayable, Jsonable, Countable
{
    /**
     * NotificationBag container name.
     *
     * @var string
     */
    protected $container = null;

    /**
     * Available message types in container.
     *
     * @var array
     */
    protected $types = array();

    /**
     * Array of matcher for extracting types.
     *
     * @var array
     */
    protected $matcher = array(
        'add'       => '{type}',
        'instant'   => '{type}Instant',
        'clear'     => 'clear{uType}',
        'show'      => 'show{uType}',
    );

    /**
     * Default format for all message types.
     *
     * @var string
     */
    protected $defaultFormat = null;

    /**
     * Default formats for types.
     *
     * @var array
     */
    protected $formats = array();

    /**
     * Collection to store all instant notification messages.
     *
     * @var \Krucas\Notification\Collection|null
     */
    protected $notifications;

    /**
     * Sequence of how messages should be rendered by its type.
     *
     * @var array
     */
    protected $groupForRender = array();

    /**
     * Notification library instance.
     *
     * @var \Krucas\Notification\Notification
     */
    protected $notification;

    /**
     * Creates new NotificationBag object.
     *
     * @param $container
     * @param array $types
     * @param null $defaultFormat
     * @param array $formats
     */
    public function __construct($container, $types = array(), $defaultFormat = null, $formats = array())
    {
        $this->container = $container;
        $this->addType($types);
        $this->setDefaultFormat($defaultFormat);
        $this->setFormats($formats);
        $this->notifications = new Collection();
    }

    /**
     * Returns assigned container name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->container;
    }

    /**
     * Add new available type of message to bag.
     *
     * @param $type
     * @return \Krucas\Notification\NotificationsBag
     */
    public function addType($type)
    {
        if (func_num_args() > 1) {
            foreach (func_get_args() as $t) {
                $this->addType($t);
            }
        } else {
            if (is_array($type)) {
                foreach ($type as $t) {
                    $this->addType($t);
                }
            } else {
                if (!$this->typeIsAvailable($type)) {
                    $this->types[] = $type;
                }
            }
        }

        return $this;
    }

    /**
     * Return available types of messages in container.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Determines if type is available in container.
     *
     * @param $type
     * @return bool
     */
    public function typeIsAvailable($type)
    {
        return in_array($type, array_values($this->types)) ? true : false;
    }

    /**
     * Resets types values.
     *
     * @return \Krucas\Notification\NotificationsBag
     */
    public function clearTypes()
    {
        $this->types = array();

        return $this;
    }

    /**
     * Extract type from a given string.
     *
     * @param $name
     * @return bool|array
     */
    protected function extractType($name)
    {
        if (count($this->types) <= 0) {
            return false;
        }

        foreach ($this->types as $type) {
            foreach ($this->matcher as $function => $pattern) {
                if (str_replace(array('{type}', '{uType}'), array($type, ucfirst($type)), $pattern) === $name) {
                    return array($type, $function);
                }
            }
        }

        return false;
    }

    /**
     * Set default format for all message types.
     *
     * @param $format
     * @return \Krucas\Notification\NotificationsBag
     */
    public function setDefaultFormat($format)
    {
        $this->defaultFormat = $format;

        return $this;
    }

    /**
     * Return default format.
     *
     * @return string
     */
    public function getDefaultFormat()
    {
        return $this->defaultFormat;
    }

    /**
     * Set formats for a given types.
     *
     * @param $formats
     * @return \Krucas\Notification\NotificationsBag
     */
    public function setFormats($formats)
    {
        foreach ($formats as $type => $format) {
            $this->setFormat($type, $format);
        }

        return $this;
    }

    /**
     * Set format for a given type.
     *
     * @param $type
     * @param $format
     * @return \Krucas\Notification\NotificationsBag
     */
    public function setFormat($type, $format)
    {
        if ($this->typeIsAvailable($type)) {
            $this->formats[$type] = $format;
        }

        return $this;
    }

    /**
     * Return format for a given type.
     *
     * @param $type
     * @return bool|string
     */
    public function getFormat($type)
    {
        if (!$this->typeIsAvailable($type)) {
            return false;
        }

        if (isset($this->formats[$type])) {
            return $this->formats[$type];
        }

        if (!is_null($this->getDefaultFormat())) {
            return $this->getDefaultFormat();
        }

        return false;
    }

    /**
     * Clear format for a given type.
     *
     * @param $type
     * @return \Krucas\Notification\NotificationsBag
     */
    public function clearFormat($type)
    {
        unset($this->formats[$type]);

        return $this;
    }

    /**
     * Clear all formats.
     *
     * @return \Krucas\Notification\NotificationsBag
     */
    public function clearFormats()
    {
        $this->formats = array();

        return $this;
    }

    /**
     * Returns valid format.
     *
     * @param $format
     * @param null $type
     * @return null
     */
    protected function checkFormat($format, $type = null)
    {
        return !is_null($format) ? $format : $this->getFormat($type);
    }

    /**
     * Adds new notification message to one of collections.
     * If message is array, adds multiple messages.
     * Message can be string, array (array can contain string for message, or array of message and format).
     * Flashes flashable messages.
     *
     * @param $type
     * @@param string|array $message
     * @param bool $flashable
     * @param null $format
     * @return \Krucas\Notification\NotificationsBag
     */
    public function add($type, $message, $flashable = true, $format = null)
    {
        if (!$this->typeIsAvailable($type)) {
            return $this;
        }

        if (is_array($message)) {
            $this->addArray($type, $message, $flashable, $format);
        } else {
            if ($message instanceof \Krucas\Notification\Message) {
                $m = $message;
                $m->setType($type);
                if ($m->isFlashable() != $flashable) {
                    $m->setFlashable($flashable);
                }
                if (is_null($m->getFormat())) {
                    $m->setFormat($this->getFormat($type));
                }
                if (!is_null($format)) {
                    $m->setFormat($this->checkFormat($format, $type));
                }
            } else {
                $m = new Message($type, $message, $flashable, $this->checkFormat($format, $type));
            }

            if (!$m->isFlashable()) {
                if (!is_null($m->getAlias())) {
                    $this->addAliased($m);
                } else {
                    if (!is_null($m->getPosition())) {
                        $this->notifications->setAtPosition($m->getPosition(), $message);
                    } else {
                        $this->notifications->addUnique($m);
                    }
                }
                $this->fireEvent('added', $m);
            } else {
                $this->fireEvent('flash', $m);
            }
        }

        return $this;
    }

    /**
     * Add array of messages to container.
     *
     * @param $type
     * @param array $messages
     * @param bool $flashable
     * @param null $defaultFormat
     * @return void
     */
    protected function addArray($type, $messages = array(), $flashable = true, $defaultFormat = null)
    {
        foreach ($messages as $message) {
            if (is_array($message)) {
                $text = $format = $alias = $position = null;
                if (isset($message['message'])) {
                    $text = $message['message'];

                    if (isset($message['alias'])) {
                        $alias = $message['alias'];
                    }

                    if (isset($message['position'])) {
                        $position = $message['position'];
                    }

                    if (isset($message['format'])) {
                        $format = $message['format'];
                    }
                } elseif (count($message) == 2) {
                    $text = $message[0];
                    $format = $message[1];
                }
                $this->add(
                    $type,
                    new Message(
                        $type,
                        $text,
                        $flashable,
                        is_null($format) ? $defaultFormat : $format,
                        $alias,
                        $position
                    ),
                    $flashable
                );
            } else {
                $this->add($type, $message, $flashable, $defaultFormat);
            }
        }
    }

    /**
     * Add message with alias.
     *
     * @param $message
     * @return void
     */
    protected function addAliased($message)
    {
        $inserted = false;

        foreach ($this->notifications as $m) {
            if ($message->getAlias() == $m->getAlias()) {
                $index = $this->notifications->indexOf($m);

                if ($index !== false) {
                    $this->notifications->offsetUnset($index);
                    $this->notifications->setAtPosition(
                        is_null($message->getPosition()) ? $index : $message->getPosition(),
                        $message
                    );
                    $inserted = true;
                }
            }
        }

        if (!$inserted) {
            if (!is_null($message->getPosition())) {
                $this->notifications->setAtPosition($message->getPosition(), $message);
            } else {
                $this->notifications->addUnique($message);
            }
        }
    }

    /**
     * Returns all messages for given type.
     *
     * @param $type
     * @return \Krucas\Notification\Collection
     */
    public function get($type)
    {
        $collection = new Collection();

        foreach ($this->notifications as $key => $message) {
            if ($message->getType() == $type) {
                if (!is_null($message->getPosition())) {
                    $collection->setAtPosition($key, $message);
                } else {
                    $collection->addUnique($message);
                }
            }
        }

        return $collection;
    }

    /**
     * Clears message for a given type.
     *
     * @param null $type
     * @return \Krucas\Notification\NotificationBag
     */
    public function clear($type = null)
    {
        if (is_null($type)) {
            $this->notifications = new Collection();
        } else {
            foreach ($this->notifications as $key => $message) {
                if ($message->getType() == $type) {
                    $this->notifications->offsetUnset($key);
                }
            }
        }

        return $this;
    }

    /**
     * Clears all messages.
     * Alias for clear(null).
     *
     * @return \Krucas\Notification\NotificationBag
     */
    public function clearAll()
    {
        return $this->clear(null);
    }

    /**
     * Returns all messages in bag.
     *
     * @return \Krucas\Notification\Collection
     */
    public function all()
    {
        return $this->notifications;
    }

    /**
     * Returns first message object for given type.
     *
     * @return \Krucas\Notification\Message
     */
    public function first()
    {
        return $this->notifications->first();
    }

    /**
     * Returns generated output of non flashable messages.
     *
     * @param null $type
     * @param null $format
     * @return string
     */
    public function show($type = null, $format = null)
    {
        $messages = $this->getMessagesForRender($type);

        $this->groupForRender = array();

        $output = '';

        foreach ($messages as $message) {
            if (!$message->isFlashable()) {
                if (!is_null($format)) {
                    $message->setFormat($format);
                }

                $output .= $message->render();
            }
        }

        return $output;
    }

    /**
     * Renders all messages.
     *
     * @param null $format
     * @return string
     */
    public function showAll($format = null)
    {
        return $this->show(null, $format);
    }

    /**
     * Resolves which messages should be returned for rendering.
     *
     * @param null $type
     * @return \Krucas\Notification\Collection
     */
    protected function getMessagesForRender($type = null)
    {
        if (is_null($type)) {
            if (count($this->groupForRender) > 0) {
                $messages = array();

                foreach ($this->groupForRender as $typeToRender) {
                    $messages = array_merge($messages, $this->get($typeToRender)->all());
                }

                return new Collection($messages);
            }

            return $this->all();
        }
        return $this->get($type);
    }

    /**
     * Return array with groups list for rendering.
     *
     * @return array
     */
    public function getGroupingForRender()
    {
        return $this->groupForRender;
    }

    /**
     * Set order to render types.
     * Call this method: group('success', 'info', ...)
     *
     * @return \Krucas\Notification\NotificationsBag
     */
    public function group()
    {
        if (func_num_args() > 0) {
            $types = func_get_args();
            $this->groupForRender = array();
            foreach ($types as $type) {
                $this->addToGrouping($type);
            }
        }

        return $this;
    }

    /**
     * Adds type for rendering.
     *
     * @param $type
     * @return \Krucas\Notification\NotificationsBag
     */
    public function addToGrouping($type)
    {
        if (!$this->typeIsAvailable($type)) {
            return $this;
        }

        if (!in_array($type, $this->groupForRender)) {
            $this->groupForRender[] = $type;
        }

        return $this;
    }

    /**
     * Removes type from rendering.
     *
     * @param $type
     * @return \Krucas\Notification\NotificationsBag
     */
    public function removeFromGrouping($type)
    {
        foreach ($this->groupForRender as $key => $typeToRender) {
            if ($type == $typeToRender) {
                unset($this->groupForRender[$key]);
            }
        }

        $this->groupForRender = array_values($this->groupForRender);

        return $this;
    }

    /**
     * Returns messages at given position.
     * Shortcut to all()->getAtPosition().
     *
     * @param $position
     * @return \Krucas\Notification\Message
     */
    public function getAtPosition($position)
    {
        return $this->all()->getAtPosition($position);
    }

    /**
     * Returns message with a given alias or null if not found.
     *
     * @param $alias
     * @return \Krucas\Notification\Message|null
     */
    public function getAliased($alias)
    {
        return $this->all()->getAliased($alias);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = array
        (
            'container'         => $this->container,
            'format'            => $this->getDefaultFormat(),
            'types'             => $this->getTypes(),
            'notifications'     => $this->notifications->toArray()
        );

        return $arr;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the Bag to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->notifications;
    }

    /**
     * Count the number of colections.
     *
     * @return int
     */
    public function count()
    {
        return count($this->notifications);
    }

    /**
     * Fire event for a given message.
     *
     * @param $event
     * @param $message
     * @return boolean
     */
    protected function fireEvent($event, $message)
    {
        if (!isset($this->notification)) {
            return true;
        }

        return $this->getNotification()->fire($event, $this, $message);
    }

    /**
     * Set notification instance.
     *
     * @param \Krucas\Notification\Notification $notification
     * @return void
     */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get notification instance.
     *
     * @return \Krucas\Notification\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Unset notification instance.
     *
     * @return void
     */
    public function unsetNotification()
    {
        $this->notification = null;
    }

    /**
     * Execute short version of function calls.
     *
     * @param $name
     * @param $arguments
     * @return \Krucas\Notification\NotificationsBag|string
     */
    public function __call($name, $arguments)
    {
        if (($extracted = $this->extractType($name)) !== false) {
            switch($extracted[1]) {
                case 'add':
                    return $this->add(
                        $extracted[0],
                        isset($arguments[0]) ? $arguments[0] : null,
                        true,
                        isset($arguments[1]) ? $arguments[1] : null
                    );
                    break;

                case 'instant':
                    return $this->add(
                        $extracted[0],
                        isset($arguments[0]) ? $arguments[0] : null,
                        false,
                        isset($arguments[1]) ? $arguments[1] : null
                    );
                    break;

                case 'clear':
                    return $this->clear($extracted[0]);
                    break;

                case 'show':
                    return $this->show($extracted[0], isset($arguments[0]) ? $arguments[0] : null);
                    break;
            }
        }
    }
}
