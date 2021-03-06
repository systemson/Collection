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

use Amber\Collection\Contracts\CollectionInterface;
use Amber\Collection\Contracts\PairInterface;
use Amber\Collection\Base\ArrayFunctionsTrait;
use Amber\Collection\Base\StatementsTrait;
use Amber\Collection\Base\AliasesTrait;
use Amber\Collection\Base\MixedKeysEncapsulationTrait;
use Amber\Collection\Base\EssentialTrait;
use Amber\Collection\Implementations\Pair;
use Amber\Collection\Implementations\NullablePair;

/**
 * A sequential collection of key-value pairs that can use objects as keys.
 *
 * @todo MUST remove all sequential array methods.
 */
class Map extends CollectionCommons implements CollectionInterface
{
    use EssentialTrait,
        MixedKeysEncapsulationTrait,
        ArrayFunctionsTrait,
        StatementsTrait,
        AliasesTrait
    ;

    /**
     * Collection consructor.
     *
     * @param array $array The items for the new collection.
     */
    public function __construct($array = [])
    {
        $this->setMultiple($this->extractArray($array));
    }

    /**
     * @param mixed $offset
     *
     * @return PairInterface
     */
    protected function getPair($offset): PairInterface
    {
        foreach ($this->storage as $index => $pair) {
            if ($pair->getKey() === $offset) {
                $pair->index = $index;
                return $pair;
            }
        }

        return new NullablePair($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $pair = $this->getPair($offset);
            $pair->setValue($value);
        } else {
            parent::offsetSet(null, new Pair($offset, $value));
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset)
    {
        return !$this->getPair($offset)->isEmpty();
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->getPair($offset)->clear();
    }

    /**
     * @param mixed $offset
     */
    public function &offsetGet($offset)
    {
        $ret =& $this->getPair($offset)->getValue();

        return $ret;
    }

    public function toArray(): array
    {
        $ret = [];

        foreach (parent::toArray() as $item) {
            $ret[$item->getKey()] = $item->getValue();
        }

        return $ret;
    }
}
