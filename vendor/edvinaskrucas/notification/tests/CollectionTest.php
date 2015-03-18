<?php

use Mockery as m;

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testCollectionConstructor()
    {
        $collection = $this->getCollection();

        $this->assertInstanceOf('Krucas\Notification\Collection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testAddingMessagesToCollection()
    {
        $collection = $this->getCollection();
        $this->assertCount(0, $collection);

        $collection->add(new \Krucas\Notification\Message());
        $this->assertCount(1, $collection);

        $collection->add(new \Krucas\Notification\Message());
        $this->assertCount(2, $collection);
    }

    public function testContainsMethod()
    {
        $collection = $this->getCollection();

        $collection->add(new \Krucas\Notification\Message());

        $this->assertCount(1, $collection);
        $this->assertTrue($collection->contains(new \Krucas\Notification\Message()));
        $this->assertFalse($collection->contains(new \Krucas\Notification\Message('error')));
    }

    public function testAddUniqueMessages()
    {
        $collection = $this->getCollection();
        $this->assertCount(0, $collection);

        $collection->addUnique(new \Krucas\Notification\Message());
        $this->assertCount(1, $collection);

        $collection->addUnique(new \Krucas\Notification\Message());
        $this->assertCount(1, $collection);
    }

    public function testCollectionRender()
    {
        $collection = $this->getCollection();

        $collection->add(new \Krucas\Notification\Message('error', 'error message', false, ':type: :message'));
        $collection->add(new \Krucas\Notification\Message('warning', 'w', false, ':message'));

        $this->assertCount(2, $collection);
        $this->assertEquals('error: error messagew', $collection->render());
    }

    public function testCollectionToString()
    {
        $collection = $this->getCollection();

        $collection->add(new \Krucas\Notification\Message('error', 'error message', false, ':type: :message'));
        $collection->add(new \Krucas\Notification\Message('warning', 'w', false, ':message'));

        $this->assertCount(2, $collection);
        $this->assertEquals('error: error messagew', (string)$collection);
    }

    public function testIndexOf()
    {
        $collection = $this->getCollection();

        $m1 = new \Krucas\Notification\Message('error', 'm');
        $m2 = new \Krucas\Notification\Message('info', 'm');
        $m3 = new \Krucas\Notification\Message('error', 'm2');
        $m4 = new \Krucas\Notification\Message('error', 'm');

        $collection->addUnique($m1)->addUnique($m2)->addUnique($m3);

        $this->assertCount(3, $collection);
        $this->assertEquals(0, $collection->indexOf($m4));
    }

    public function testSetAtPosition()
    {
        $collection = $this->getCollection();

        $collection
            ->addUnique(new \Krucas\Notification\Message('info', 'i'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i2'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i3'))
            ->setAtPosition(2, new \Krucas\Notification\Message('info', 'info'));

        $this->assertCount(4, $collection);
        $this->assertEquals('info', $collection->getAtPosition(2)->getMessage());
        $this->assertEquals('i3', $collection->getAtPosition(3)->getMessage());
    }

    public function testSetAtPositionAndThenAddMessage()
    {
        $collection = $this->getCollection();

        $collection
            ->setAtPosition(2, new \Krucas\Notification\Message('info', 'info'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i'));

        $this->assertCount(2, $collection);
        $this->assertEquals('info', $collection->getAtPosition(2)->getMessage());
        $this->assertEquals('i', $collection->getAtPosition(0)->getMessage());
    }

    public function testSetAtPositionAndAddLotOfMessagesAtTheBeginning()
    {
        $collection = $this->getCollection();

        $collection
            ->setAtPosition(2, new \Krucas\Notification\Message('info', 'info'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i1'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i2'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i3'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i4'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i5'))
            ->addUnique(new \Krucas\Notification\Message('info', 'i6'));

        $this->assertCount(7, $collection);
        $this->assertEquals('info', $collection->getAtPosition(2)->getMessage());
        $this->assertEquals('i1', $collection->getAtPosition(0)->getMessage());
        $this->assertEquals('i6', $collection->getAtPosition(6)->getMessage());
    }

    public function testSetTwoMessagesAtSamePosition()
    {
        $collection = $this->getCollection();

        $collection
            ->setAtPosition(20, new \Krucas\Notification\Message('info', 'info'))
            ->setAtPosition(20, new \Krucas\Notification\Message('info', 'info2'));

        $this->assertCount(2, $collection);
        $this->assertEquals('info2', $collection->getAtPosition(20)->getMessage());
        $this->assertEquals('info', $collection->getAtPosition(21)->getMessage());
    }

    public function testAddAtDifferentPositions()
    {
        $collection = $this->getCollection();

        $collection
            ->setAtPosition(5, new \Krucas\Notification\Message('info', 'info'))
            ->setAtPosition(3, new \Krucas\Notification\Message('info', 'info2'))
            ->setAtPosition(8, new \Krucas\Notification\Message('info', 'info3'))
            ->addUnique(new \Krucas\Notification\Message('info', 'info4'));

        $this->assertCount(4, $collection);
        $this->assertEquals('info', $collection->getAtPosition(5)->getMessage());
        $this->assertEquals('info2', $collection->getAtPosition(3)->getMessage());
        $this->assertEquals('info3', $collection->getAtPosition(8)->getMessage());
        $this->assertEquals('info4', $collection->getAtPosition(0)->getMessage());
    }

    public function testGetAliasedMessage()
    {
        $collection = $this->getCollection();

        $collection
            ->addUnique(new \Krucas\Notification\Message('info', 'info', false, '', 'a'))
            ->addUnique(new \Krucas\Notification\Message('error', 'error', false, '', 'b'))
            ->addUnique(new \Krucas\Notification\Message('warning', 'warning', false, '', 'c'));

        $this->assertCount(3, $collection);
        $this->assertEquals('info', $collection->getAliased('a')->getMessage());
        $this->assertEquals('error', $collection->getAliased('b')->getMessage());
        $this->assertEquals('warning', $collection->getAliased('c')->getMessage());
        $this->assertNull($collection->getAliased('d'));
    }

    protected function getCollection()
    {
        return new \Krucas\Notification\Collection();
    }
}
