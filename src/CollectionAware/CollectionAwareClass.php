<?php

namespace Amber\Collection\CollectionAware;

/**
 * Implements the CollectionAwareInterface
 */
abstract class CollectionAwareClass implements CollectionAwareInterface
{
    use CollectionAwareTrait;

    public function __construct(array $array = [])
    {
        $this->initCollection($array);
    }
}
