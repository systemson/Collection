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

/**
 * Wrapper class for working with arrays.
 *
 * @todo MUST add support for searching wildcars. Like: $collection->get('base.{*}.other');
 *       SHOULD return an array if many items are found, else the matching item.
 */
class MultilevelCollection extends ArrayCollection
{
    /**
     * @var string The separator for multilevel keys.
     */
    protected $separator = '.';

    /**
     * @var boolean Is the collection multileveled.
     */
    protected $multilevel = true;

    /**
     * Collection constructor.
     *
     * @param array $array      The items for the collection.
     * @param bool  $multilevel Defines if the array should handle multilevel keys.
     */
    public function __construct($array = [], bool $multilevel = true)
    {
        parent::__construct($array);

        $this->multilevel = $multilevel;
    }

    /**
     * Splits a multilevel key or returns the single level key(s).
     *
     * @param string $key The key to split.
     *
     * @return array|string An array of keys or a single key string.
     */
    protected function splitKey(string $key)
    {
        if (!$this->multilevel) {
            return $key;
        }

        $array = explode($this->separator, $key);

        if (count($array) == 1) {
            return $key;
        }

        return $array;
    }

    /**
     * Sets or updates an item in the collection.
     *
     * @param string $key   The item's key
     * @param mixed  $value The item's value
     *
     * @return void
     */
    public function set(string $key, $value = null): void
    {
        $slug = $this->splitKey($key);

        if (is_string($slug)) {
            $this[$slug] = $value;
            return;
        }

        $storage = $value;

        foreach (array_reverse($slug) as $id => $key) {
            $aux = [];

            if ($id === count($slug) - 1) {
                break;
            }

            $aux[$key] = $storage;

            $storage = $aux;
            unset($aux);
        }

        $this[$key] = $storage;
    }

    /**
     * Whether an item is present it the collection
     *
     * @param string $key The item's key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        $slug = $this->splitKey($key);

        if (is_string($slug)) {
            return isset($this[$slug]);
        }

        $collection = $this->all();

        foreach ($slug as $search) {
            if (!isset($collection[$search])) {
                return false;
            }

            $collection = $collection[$search];
        }

        return true;
    }

    /**
     * Gets an item from collection.
     *
     * @param string $key The item's key
     * @param mixed  $default The default value if the key doesn't exists.
     *
     * @return mixed|void The item's value or $default if the key doesn't exists.
     */
    public function get(string $key, $default = null)
    {
        $slug = $this->splitKey($key);

        if (is_string($slug)) {
            if (isset($this[$slug])) {
                return $this[$slug];
            }
            return $default;
        }

        $array = $this->toArray();

        foreach ($slug as $search) {
            if (isset($array[$search])) {
                $array = $array[$search];
            } else {
                return;
            }
        }

        return $array;
    }

    /**
     * Unsets an item from collection.
     *
     * @param string $key The item's key
     *
     * @return void
     */
    public function unset(string $key): void
    {
        $slug = $this->splitKey($key);

        if (is_string($slug)) {
            if (isset($this[$slug])) {
                unset($this[$slug]);
            }
        } else {
            if ($this->has($key)) {
                $this->set($key, null);
            }
        }
    }
}
