<?php namespace Krucas\Notification;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection implements Renderable
{
    /**
     * Add message to collection.
     *
     * @param Message $message
     * @return \Krucas\Notification\Collection
     */
    public function add(Message $message)
    {
        if ($this->count() > 0) {
            for ($i = 0; $i <= $this->indexOf($this->last()) + 1; $i++) {
                if (!$this->offsetExists($i)) {
                    $this->setAtPosition($i, $message);
                    return $this;
                }
            }
        }

        $this->items[] = $message;

        return $this;
    }

    /**
     * Adds message to collection only if it is unique.
     *
     * @param Message $message
     * @return \Krucas\Notification\Collection
     */
    public function addUnique(Message $message)
    {
        if (!$this->contains($message)) {
            return $this->add($message);
        }

        return $this;
    }

    /**
     * Sets item at given position.
     *
     * @param $position
     * @param \Krucas\Notification\Message $message
     * @return \Krucas\Notification\Collection
     */
    public function setAtPosition($position, Message $message)
    {
        $tmp = array();

        array_set($tmp, $position, $message);

        foreach ($this->items as $key => $item) {
            $i = $key;
            while (array_key_exists($i, $tmp)) {
                $i++;
            }
            $tmp[$i] = $item;
        }

        $this->items = $tmp;

        ksort($this->items);

        return $this;
    }

    /**
     * Returns item on a given position.
     *
     * @param $position
     * @return \Krucas\Notification\Message
     */
    public function getAtPosition($position)
    {
        return $this->offsetGet($position);
    }

    /**
     * Returns aliased message or null if not found.
     *
     * @param $alias
     * @return \Krucas\Notification\Message|null
     */
    public function getAliased($alias)
    {
        foreach ($this as $message) {
            if ($message->getAlias() == $alias) {
                return $message;
            }
        }

        return null;
    }

    /**
     * Returns index value of a given message.
     *
     * @param Message $message
     * @return bool|int
     */
    public function indexOf(Message $message)
    {
        foreach ($this as $index => $m) {
            if ($message === $m) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $output = '';

        foreach ($this->items as $message) {
            $output .= $message->render();
        }

        return $output;
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
