<?php

namespace Amber\Collection\Base;

use IteratorAggregate;
use ArrayAccess;
use Serializable;
use Countable;
use Amber\Collection\Implementations\{
    PropertyAccessTrait,
    IteratorAggregateTrait,
    SerializableTrait,
    CountableTrait
};

class ArrayObject implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    use PropertyAccessTrait, IteratorAggregateTrait, SerializableTrait, CountableTrait;

    protected $storage = [];

    public function __construct(array $array = [])
    {
        $this->storage = $array;
    }

    public function exchangeArray(array $array): void
    {
        $this->storage = $array;
    }

    public function getArrayCopy(): array
    {
        return $this->storage;
    }
}