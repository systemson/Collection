<?php

namespace Tests;

use Amber\Config\ConfigAwareTrait;
use Amber\Collection\Collection;
use Amber\Collection\CollectionAware\CollectionAwareClass;
use PHPUnit\Framework\TestCase;

class BaseCollectionTest extends TestCase
{
    public function testAssociativeCollection()
    {
        $collection = new Collection();

        /* Tests keys that don't exists */
        $this->assertTrue($collection->hasNot('key'));
        $this->assertTrue($collection->hasNot('key1'));

        /* Tests updating a key that doesn't exists */
        $this->assertFalse($collection->update('key1', 'value'));

        /* Tests adding items */
        $this->assertNull($collection->put('key', 'value'));
        $this->assertTrue($collection->insert('key1', 'value'));

        /* Tests adding an item that already exists */
        $this->assertFalse($collection->insert('key1', 'value'));

        /* Tests updating an item */
        $this->assertTrue($collection->update('key1', 'value1'));

        /* Tests that items exist */
        $this->assertTrue($collection->has('key'));
        $this->assertTrue($collection->has('key1'));

        /* Tests getting items */
        $this->assertEquals('value', $collection->get('key'));
        $this->assertEquals('value1', $collection->find('key1'));
        $this->assertEquals('value', $collection->first());
        $this->assertEquals('value1', $collection->last());

        /* Tests removing an item */
        $this->assertTrue($collection->delete('key'));
        $this->assertEquals('value1', $collection->remove('key1'));
        $this->assertNull($collection->remove('key1'));

        /* Tests that the item doesn't exists */
        $this->assertNull($collection->get('key'));
        $this->assertNull($collection->get('key1'));
        $this->assertFalse($collection->has('key'));
        $this->assertFalse($collection->has('key1'));
        $this->assertNull($collection->remove('key'));
        $this->assertNull($collection->remove('key1'));
        
        /* Tests removing an item that doesn't exists */
        $this->assertFalse($collection->delete('key'));

        /* Test pushing to a key */
        // After adding multilevel keys should be moved to another test.
        $this->assertTrue($collection->pushTo('key2', 'value1'));
        $this->assertTrue($collection->pushTo('key2', 'value2'));
        $this->assertEquals(['value1', 'value2'], $collection->get('key2'));

        /* Cleares the collection */
        $collection->clear();

        return $collection;
    }
        
    /**
     * @depends testAssociativeCollection
     */
    public function testToString($collection)
    {
        $key = 'key';
        $value = 'value';
        $json = json_encode([$key => $value]);

        $this->assertNull($collection->put($key, $value));

        $this->assertInternalType('string', (string) $collection);

        $this->assertEquals($json, (string) $collection);

        $collection->clear();

        return $collection;
    }
        
    /**
     * @depends testToString
     */
    public function testMultiple($collection)
    {
        $multiple = $this->exampleArray(5);

        $this->assertTrue($collection->setMultiple($multiple));
        $this->assertTrue($collection->setMultiple($multiple));
        $this->assertTrue($collection->setMultiple($multiple));

        $this->assertTrue($collection->hasMultiple(array_keys($multiple)));

        $this->assertEquals(array_values($multiple), $collection->getMultiple(array_keys($multiple)));

        $collection->clear();

        $this->assertFalse($collection->hasMultiple(array_keys($multiple)));

        return $collection;
    }
        
    /**
     * @depends testMultiple
     */
    public function testNumericCollection($collection)
    {
        $collection->push('value');

        $this->assertTrue($collection->has(0));

        $this->assertEquals('value', $collection->get(0));
        
        $this->assertEquals(['value'], $collection->values());

        $collection->remove(0);

        $this->assertFalse($collection->has(0));

        /* Cleares the collection */
        $collection->clear();

        return $collection;
    }

    public function testArrayFuncs()
    {
        $sumable = [1, 2, 3, 4];

        $collection = Collection::make($sumable);

        $this->assertEquals(array_sum($sumable), $collection->sum());

        $array = $this->exampleArray(5);

        $collection->exchangeArray($array);

        $this->assertEquals(array_column($array, 'name'), $collection->column('name')->toArray());

        $this->assertEquals(array_flip(array_column($array, 'name')), $collection->column('name')->flip()->toArray());
    }

    protected function exampleArray(int $qty): array
    {
		for ($x = 1; $x <= $qty; $x++) {
            $array['key_'.$x] = [
                'id'    => $x,
                'name'  => 'Pruebas' . $x,
                'pass'  => 'pass' . $x,
                'email' => "email{$x}@email.com",
            ];
        }

        return $array;
    }
}
