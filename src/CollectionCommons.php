<?php
/**
 * This file is part of the Amber/Collection package.
 *
 * @package Amber/Collection
 * @author  Deivi Peña <systemson@gmail.com>
 * @license GPL-3.0-or-later
 * @license https://opensource.org/licenses/gpl-license GNU Public License
 */

namespace Amber\Collection;

use Amber\Collection\Implementations\IteratorAggregateTrait;
use Amber\Collection\Implementations\ArrayAccessTrait;
use Amber\Collection\Implementations\PropertyAccessTrait;
use Amber\Collection\Implementations\SerializableTrait;
use Amber\Collection\Implementations\CountableTrait;
use Amber\Collection\Contracts\CollectionInterface;
use Amber\Collection\Contracts\Arrayable;

/**
 * Implements the CollectionInterface contract
 *
 * @todo Secuential and Paired collections COULD extend SplFixedArray
 * @todo Asociative collections COULD extend ArrayObject
 */
abstract class CollectionCommons implements CollectionInterface
{
    use IteratorAggregateTrait,
        ArrayAccessTrait,
        PropertyAccessTrait,
        SerializableTrait,
        CountableTrait
    ;

    /**
     * Creates a new collection.
     *
     * @param array|Arrayable $array The items for the new collection.
     *
     * @return CollectionInterface a new Instance of the collection.
     */
    public static function make($array = []): CollectionInterface
    {
        return new static($array);
    }

    /**
     * Collection constructor.
     *
     * @param array|Arrayable $array The items for the new collection.
     */
    public function __construct($array = [])
    {
        $this->storage = $this->extractArray($array);
    }

    /**
     * @param iterable $storage
     */
    protected function extractArray(iterable $storage = []): array
    {
        if (is_array($storage)) {
            return $storage;
        }

        if ($storage instanceof Arrayable || method_exists($storage, 'toArray')) {
            return $storage->toArray();
        }

        if (method_exists($storage, 'getArrayCopy')) {
            $storage = $storage->getArrayCopy();
        }

        return $storage;
    }

    /**
     * Removes all values from the collection.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->storage = [];
    }

    /**
     * Returns a shallow copy of the collection.
     *
     * @return CollectionInterface a copy of the collection.
     */
    public function copy(): CollectionInterface
    {
        return clone $this;
    }

    /**
     * Returns whether the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    /**
     * Returns an array representation of the collection.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->storage;
    }

    /**
     * Replaces the collection storage with a new array.
     *
     * @param array $array
     *
     * @return void
     */
    public function exchangeArray(array $array): void
    {
        $this->storage = $array;
    }
}
