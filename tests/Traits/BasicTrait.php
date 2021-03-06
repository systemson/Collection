<?php

namespace Tests\Traits;


trait BasicTrait
{
    public function testBasic()
    {
        $collection = $this->newCollection();
        //dump($collection);

        // Sets a value
        $this->assertNull($collection->set('key', 'value'));

        // Checks that the value is set in the collection
        $this->assertTrue($collection->has('key'));

        // Gets the value
        $this->assertEquals('value', $collection->get('key'));
        //$this->assertEquals('value', $collection->key);
        $this->assertEquals('value', $collection['key']);
        
        // Deletes the item
        $this->assertTrue($collection->delete('key'));

        // Checks that the item is not present in the collection
        $this->assertFalse($collection->delete('key'));
        $this->assertFalse($collection->has('key'));

        // Returns null if the item does not exists in the collection.
        $this->assertNull($collection->get('key1'));
    }
}