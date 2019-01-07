<?php
/**
 * This file is part of the Amber/Collection package.
 *
 * @package Amber/Collection
 * @author Deivi Peña <systemson@gmail.com>
 * @license GPL-3.0-or-later
 * @license https://opensource.org/licenses/gpl-license GNU Public License
 */

namespace Amber\Collection\Base;

use Ds\Collection as CollectionInterface;
use Closure;

/**
 * Implementations of the PHP array functions.
 */
trait ArrayFunctionsTrait
{
    /**
     * Iterates through the collection and passes each value to the given callback.
     *
     * @param Closure $callback
     *
     * @return Collection A new collection instance.
     */
    public function map(Closure $callback): CollectionInterface
    {
        $array = array_map(
            $callback,
            $this->getArrayCopy()
        );

        return static::make($array);
    }

    /**
     * Returns a new filtered collection using a user-defined function.
     *
     * @param Closure $callback
     *
     * @return Collection A new collection instance.
     */
    public function filter(Closure $callback): CollectionInterface
    {
        $array = array_filter(
            $this->getArrayCopy(),
            $callback
        );

        return static::make(array_values($array));
    }

    /**
     * Returns a new sorted collection using a user-defined comparison function.
     *
     * @param Closure $callback The user-defined comparison function.
     *
     * @return Collection A new collection instance.
     */
    public function sort(Closure $callback): CollectionInterface
    {
        $array = $this->getArrayCopy();

        usort(
            $array,
            $callback
        );

        return static::make($array);
    }

    /**
     * Returns a new reversed collection.
     *
     * @return Collection A new collection instance.
     */
    public function reverse(): CollectionInterface
    {
        $array = array_reverse($this->getArrayCopy());

        return static::make($array);
    }

    /**
     * Returns a new collection merged with one or more arrays.
     *
     * @param array $array The array(s) to merge with the collection.
     *
     * @return Collection A new collection instance.
     */
    public function merge(...$array): CollectionInterface
    {
        array_unshift($array, $this->getArrayCopy());

        $return = call_user_func_array('array_merge', $array);

        return static::make($return);
    }

    /**
     * Split an array into chunks.
     *
     * @param int  $size          The size of each chunk.
     * @param bool $preserve_keys Whether the keys should be preserved.
     *
     * @return Collection A new collection instance.
     */
    public function chunk(int $size, bool $preserve_keys = false): CollectionInterface
    {
        $return = array_chunk($this->getArrayCopy(), $size, $preserve_keys);

        return static::make($return);
    }
}
