<?php

namespace Tests\Traits;

trait AssociativeCollectionTrait
{
    public function testAssociativeCollection()
    {
        $collection = $this->newCollection();

        /* Tests keys that don't exists */
        $this->assertTrue($collection->hasNot('key'));
        $this->assertTrue($collection->hasNot('key1'));

        /* Tests updating a key that doesn't exists */
        $this->assertFalse($collection->update('key1', 'value'));

        /* Tests adding items */
        $this->assertNull($collection->set('key', 'value'));
        $this->assertTrue($collection->add('key1', 'value1'));

        /* Tests adding an item that already exists */
        $this->assertFalse($collection->add('key1', 'value'));

        /* Tests updating an item */
        $this->assertTrue($collection->update('key1', 'value1'));

        /* Tests that items exist */
        $this->assertTrue($collection->contains('key'));
        $this->assertTrue($collection->contains('key1'));

        /* Tests getting items */
        $this->assertEquals('value', $collection->get('key'));
        $this->assertEquals('value1', $collection->get('key1'));
        $this->assertEquals('value', $collection->first());
        $this->assertEquals('value1', $collection->last());

        /* Tests removing an item */
        $this->assertTrue($collection->delete('key'));
        $this->assertEquals('value1', $collection->remove('key1'));
        $this->assertNull($collection->remove('key1'));

        /* Tests that the item doesn't exists */
        $this->assertNull($collection->get('key'));
        $this->assertNull($collection->get('key1'));
        $this->assertFalse($collection->contains('key'));
        $this->assertFalse($collection->contains('key1'));
        $this->assertNull($collection->remove('key'));
        $this->assertNull($collection->remove('key1'));
        $this->assertNull($collection->first());
        $this->assertNull($collection->last());
        
        /* Tests removing an item that doesn't exists */
        $this->assertFalse($collection->delete('key'));
    }
}