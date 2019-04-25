<?php

namespace Tests;

use Amber\Collection\Implementations\Pair;
use PHPUnit\Framework\TestCase;

class PairTest extends TestCase
{
    public function testBasic()
    {
        $pair = new Pair('key', 'value');

        // Checks that the value is set in the pair
        $this->assertTrue($pair->key == 'key');
        $this->assertTrue($pair->value == 'value');

        // Gets the value
        $this->assertEquals('key', $pair->key);
        $this->assertEquals('value', $pair->value);
        $this->assertFalse($pair->isEmpty());
        $this->assertEquals(['key'=>'key', 'value'=>'value'], $pair->toArray());
        
        // Deletes the value
        $this->assertNull($pair->clear());

        // Checks that the item is not present in the collection
        $this->assertNull($pair->value);
        $this->assertFalse($pair->value == 'value');
        $this->assertTrue($pair->isEmpty());

        $this->assertEquals($pair, $pair->copy());
        $this->assertInstanceOf(Pair::class, $pair->copy());

        $this->assertEquals(['key'=>'key', 'value'=>null], $pair->toArray());

        $this->assertEquals(
        	json_encode(['key'=>'key', 'value'=>null]),
        	json_encode($pair)
        );
    }
}