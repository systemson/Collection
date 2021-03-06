<?php

namespace Tests\Traits;

use Amber\Collection\Contracts\CollectionInterface;

trait CommonTrait
{
    private function newCollection(array $array = [])
    {
        return new $this->collection($array);
    }

    private function newArray(int $n = 5, string $key = '')
    {
        for ($x = 1; $x <= $n; $x++) {
            $multiple["{$key}{$x}"] = [
                'id'    => $x,
                'name'  => 'Pruebas' . $x,
                'pass'  => 'pass' . $x,
                'email' => "email{$x}@email.com",
            ];
        }

        return $multiple;
    }

    public function testClone()
    {
        $collection = $this->newCollection();

        $this->assertInstanceOf($this->collection, $collection->clone());
        $this->assertInstanceOf(CollectionInterface::class, $collection);

        $this->assertEquals(clone $collection, $collection->clone());
    }

    public function testEssentials()
    {
        $array = $this->newArray(1);
        $collection = $this->newCollection($array);

        $this->assertInstanceOf($this->collection, $collection);
        $this->assertInstanceOf(CollectionInterface::class, $collection);

        $this->assertEquals($array, $collection->toArray());
        $this->assertEquals($array, $collection->all());

        $this->assertEquals(array_keys($array), $collection->keys());

        $this->assertEquals(array_values($array), $collection->values());

        $this->assertNotSame($collection, $collection->copy());
        $this->assertInstanceOf($this->collection, $collection->copy());

        $this->assertNotSame($collection, $collection->clone());
        $this->assertInstanceOf($this->collection, $collection->clone());

        $this->assertEquals(1, $collection->count());

        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());

        $this->assertEquals(json_encode($array), $collection->toJson());
        $this->assertEquals(json_encode($array), $collection->toString());
        $this->assertEquals(json_encode($array), json_encode($collection));

        $this->assertEquals(json_encode($array), (string) $collection);

        $this->assertNull($collection->clear());

        $this->assertEquals(json_encode([]), $collection->toJson());
        $this->assertEquals(json_encode([]), json_encode($collection));

        $this->assertEquals(json_encode([]), (string) $collection);

        $this->assertEquals([], $collection->all());
        $this->assertEquals(0, $collection->count());
        $this->assertTrue($collection->isEmpty());
        $this->assertFalse($collection->isNotEmpty());
    }

    public function testImplode()
    {
        $string = 'Hello world';
        $array = explode(' ', $string);

        $collection = $this->collection::make($array);

        $this->assertEquals($string, $collection->implode(' '));
    }

    public function testMaxMin()
    {
        $collection = $this->newCollection();

        $this->assertFalse($collection->max());
        $this->assertFalse($collection->min());

        $array = [0, 1, 2, 3, 5, 8, 13, 21];

        $collection = $this->newCollection($array);

        $this->assertEquals(max($array), $collection->max());
        $this->assertEquals(min($array), $collection->min());
    }

    public function testOthers()
    {
        $array = [1,2,3,4,5];

        $collection = $this->newCollection($array);

        $x = 1;
        foreach ($collection as $key => $value) {
            $this->assertEquals($x, $value);
            $x++;
        }

        $serialized = serialize($collection);

        $this->assertEquals($collection, unserialize($serialized));
        $this->assertEquals(5, $collection->count());
        $this->assertNull($collection->exchangeArray([]));
        $this->assertEquals(0, $collection->count());
    }
}