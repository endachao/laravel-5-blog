<?php

use Mockery as m;

class NotificationMiddlewareTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testOnBoot()
    {
        $notificationsBag = $this->getNotificationsBag();
        $notificationsBag->shouldReceive('add')->once()->with('info', m::type('Krucas\Notification\Message'), false);
        $notificationsBag->shouldReceive('add')->once()->with('error', m::type('Krucas\Notification\Message'), false);

        $session = $this->getSessionStore();
        $notification = $this->getNotification();
        $notification->shouldReceive('container')->twice()->with('test')->andReturn($notificationsBag);
        $prefix = 'notifications_';

        $middleware = new \Krucas\Notification\Middleware\NotificationMiddleware($session, $notification, $prefix);
        $session->shouldReceive('get')->once()->with('notifications_containers', array())->andReturn(array('test'));
        $flasedMessages = array(
            'notifications_test_1' => '{"message":"test message","format":":type: :message","type":"info","flashable":false,"alias":null,"position":null}',
            'notifications_test_2' => '{"message":"test message","format":":type: :message","type":"error","flashable":false,"alias":null,"position":null}',
        );
        $session->shouldReceive('all')->once()->andReturn($flasedMessages);

        $middleware->handle(m::mock('Illuminate\Http\Request'), function() {});
    }

    protected function getSessionStore()
    {
        return m::mock('Illuminate\Session\Store');
    }

    protected function getNotification()
    {
        return m::mock('Krucas\Notification\Notification');
    }

    protected function getNotificationsBag()
    {
        return m::mock('Krucas\Notification\NotificationsBag');
    }
}
