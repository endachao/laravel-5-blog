<?php

use Mockery as m;

class MessageTest extends PHPUnit_Framework_TestCase
{
    public function testMessageConstructor()
    {
        $message = new \Krucas\Notification\Message('error', 'test message', false, ':type: :message', 'test', 4);

        $this->assertInstanceOf('Krucas\Notification\Message', $message);
        $this->assertEquals('error', $message->getType());
        $this->assertEquals('test message', $message->getMessage());
        $this->assertEquals(':type: :message', $message->getFormat());
        $this->assertEquals('test', $message->getAlias());
        $this->assertFalse($message->isFlashable());
        $this->assertEquals(4, $message->getPosition());
    }

    public function testMethodChaining()
    {
        $message = new \Krucas\Notification\Message();

        $this->assertInstanceOf('Krucas\Notification\Message', $message);
        $this->assertNull($message->getType());
        $this->assertNull($message->getMessage());
        $this->assertNull($message->getFormat());
        $this->assertTrue($message->isFlashable());
        $this->assertNull($message->getAlias());
        $this->assertNull($message->getPosition());

        $message->setFlashable(false)
            ->setFormat('Test: :message')
            ->setType('warning')
            ->setMessage('test')
            ->setAlias('test')
            ->setPosition(5);

        $this->assertEquals('warning', $message->getType());
        $this->assertEquals('test', $message->getMessage());
        $this->assertEquals('Test: :message', $message->getFormat());
        $this->assertFalse($message->isFlashable());
        $this->assertEquals('test', $message->getAlias());
        $this->assertEquals(5, $message->getPosition());
    }

    public function testToStringMethod()
    {
        $message = new \Krucas\Notification\Message('error', 'test message', false, ':type: :message');

        $this->assertEquals('error: test message', (string)$message);
    }

    public function testMessageRendering()
    {
        $message = new \Krucas\Notification\Message('error', 'test message', false, ':type: :message');

        $this->assertEquals('error: test message', $message->render());
    }

    public function testMessageToArray()
    {
        $message = new \Krucas\Notification\Message('error', 'test message', false, ':type: :message');

        $this->assertEquals(array(
            'message' => 'test message',
            'format' => ':type: :message',
            'type' => 'error',
            'flashable' => false,
            'alias' => null,
            'position' => null
        ), $message->toArray());
    }

    public function testMessageToJson()
    {
        $message = new \Krucas\Notification\Message('error', 'test message', false, ':type: :message');

        $this->assertEquals('{"message":"test message","format":":type: :message","type":"error","flashable":false,"alias":null,"position":null}', $message->toJson());
    }

    public function testMethodsShortcuts()
    {
        $message = new \Krucas\Notification\Message();
        $this->assertNull($message->getMessage());
        $this->assertNull($message->getFormat());
        $this->assertNull($message->getAlias());
        $this->assertNull($message->getPosition());
        $this->assertTrue($message->isFlashable());

        $message->message('test')->format(':message')->alias('alias')->position(5);
        $this->assertEquals('test', $message->getMessage());
        $this->assertEquals(':message', $message->getFormat());
        $this->assertEquals('alias', $message->getAlias());
        $this->assertEquals(5, $message->getPosition());
        $this->assertTrue($message->isFlashable());

        $message->instant();
        $this->assertFalse($message->isFlashable());

        $message->flash();
        $this->assertTrue($message->isFlashable());
    }
}