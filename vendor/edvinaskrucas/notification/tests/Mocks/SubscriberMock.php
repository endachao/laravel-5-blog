<?php

class SubscriberMock extends \Krucas\Notification\Subscriber
{
    public function flashContainerNames(\Krucas\Notification\Notification $notification)
    {
        return parent::flashContainerNames($notification);
    }

    public function generateMessageKey(\Krucas\Notification\Message $message)
    {
        return parent::generateMessageKey($message);
    }
}
