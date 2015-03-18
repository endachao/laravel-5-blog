<?php

class NotificationsBagMock extends \Krucas\Notification\NotificationsBag
{
    public function extractType($name)
    {
        return parent::extractType($name);
    }
}