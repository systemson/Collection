<?php
/**
 * This file is part of the Amber/Collection package.
 *
 * @package Amber/Collection
 * @author  Deivi Peña <systemson@gmail.com>
 * @license GPL-3.0-or-later
 * @license https://opensource.org/licenses/gpl-license GNU Public License
 */

namespace Amber\Collection\Base;

use Amber\Collection\Contracts\CollectionInterface;
use Closure;

/**
 * Implements basic methods for the Collection.
 */
trait EssentialTrait
{
    /**
     * Iterates through the collection and passes each value to the given callback.
     *
     * @param \Closure $callback
     *
     * @return CollectionInterface A new collection instance.
     */
    public function map(\Closure $callback): CollectionInterface
    {
        $array = array_map(
            $callback,
            $this->toArray()
        );

        return static::make($array);
    }

    /**
     * Returns a new filtered collection using a user-defined function.
     *
     * @param \Closure $callback
     *
     * @return CollectionInterface A new collection instance.
     */
    public function filter(\Closure $callback): CollectionInterface
    {
        $array = array_filter(
            $this->toArray(),
            $callback,
            ARRAY_FILTER_USE_BOTH
        );

        // Check if the array is associative.
        if (count(array_filter(array_keys($array), 'is_string')) > 0) {
            return static::make($array);
        }

        // If the array is secuential reset its keys.
        return static::make(array_values($array));
    }

    /**
     * Returns a new sorted collection using a user-defined comparison function.
     *
     * @param Closure $callback The user-defined comparison function.
     *
     * @return CollectionInterface A new collection instance.
     */
    public function sort(Closure $callback = null): CollectionInterface
    {
        $array = $this->toArray();

        if (is_null($callback)) {
            sort($array);
        } else {
            usort(
                $array,
                $callback
            );
        }

        return static::make($array);
    }

    /**
     * Returns the items that are not present in the collection and the array.
     *
     * @param array $array The array(s) to compare.
     *
     * @return CollectionInterface A new collection instance.
     */
    public function diff(array ...$array): CollectionInterface
    {
        $return = call_user_func_array('array_diff', array_merge([$this->toArray()], $array));

        return static::make($return);
    }

    /**
     * Returns a new collection merged with one or more arrays.
     *
     * @param array $array The array(s) to merge with the collection.
     *
     * @return CollectionInterface A new collection instance.
     */
    public function merge(...$array): CollectionInterface
    {
        array_unshift($array, $this->toArray());

        $return = call_user_func_array('array_merge', $array);

        return static::make($return);
    }

    /**
     * Alias for toArray().
     *
     * @return array The items in the collection.
     */
    public function all(): array
    {
        return $this->toArray();
    }

    /**
     * Retuns an array of the collection keys.
     *
     * @return array The items in the collection.
     */
    public function keys(): array
    {
        return array_keys($this->toArray());
    }

    /**
     * Retuns an array of the collection values.
     *
     * @return array The items in the collection.
     */
    public function values(): array
    {
        return array_values($this->toArray());
    }

    /**
     * Returns whether the collection is not empty.
     *
     * @return bool Whether the collection is empty.
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Joins the collection items into a string.
     *
     * @param string $glue The glue string between each element.
     *
     * @return string
     */
    public function implode(string $glue = ', '): string
    {
        return implode($glue, $this->toArray());
    }

    /**
     * Returns the max value of the collection.
     *
     * @param string $column The column to get the max value.
     *
     * @return mixed
     */
    public function max(string $column = null)
    {
        if ($this->isNotEmpty()) {
            return max($this->toArray());
        }

        return false;
    }

    /**
     * Returns the min value of the collection.
     *
     * @param string $column The column to get the min value.
     *
     * @return mixed
     */
    public function min(string $column = null)
    {
        if ($this->isNotEmpty()) {
            return min($this->toArray());
        }

        return false;
    }

    /**
     * Returns the first element of the collection.
     *
     * @return mixed
     */
    public function first()
    {
        if ($this->isNotEmpty()) {
            return current($this->toArray());
        }
    }

    /**
     * Returns the last element of the collection.
     *
     * @return mixed
     */
    public function last()
    {
        if ($this->isNotEmpty()) {
            $all = $this->toArray();
            return end($all);
        }
    }

    /**
     * Returns the items of the collection that match the specified array of keys.
     *
     * @param array $keys
     *
     * @return CollectionInterface
     */
    public function only(array $keys): CollectionInterface
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return in_array($key, $keys);
        });
    }

    /**
     * Returns the items of the collections that do not match the specified array of keys.
     *
     * @param array $keys
     *
     * @return CollectionInterface
     */
    public function except(array $keys): CollectionInterface
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return !in_array($key, $keys);
        });
    }
}
