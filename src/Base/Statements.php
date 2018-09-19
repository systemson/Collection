<?php

namespace Amber\Collection\Base;

/**
 * Adds sql like methods to the collection.
 */
trait Statements
{
    /**
     * Returns a new Collection containing the items in the specified column(s).
     *
     * @param array|string $columns The collumns to filter by.
     *
     * @return Collection A new collection.
     */
    public function select(...$columns)
    {
        $container = $this->map(function ($item) use ($columns) {
            $result = [];

            foreach ($columns as $column) {
                if (isset($item[$column])) {
                    $result[$column] = $item[$column];
                }
            }

            return $result;
        }, $this->getArrayCopy());

        return $this->make($container);
    }

    /**
     * Returns a new Collection containing the items in the specified column that are equal to the especified value.
     *
     * @param string $column The columns to filter by.
     * @param mixed  $value  The value to compare each item.
     *
     * @return Collection A new collection.
     */
    public function where($column, $value)
    {
        return $this->filter(
            function ($item) use ($column, $value) {
                if (isset($item[$column])) {
                    return $item[$column] === $value;
                }
            }
        );
    }

    /**
     * Returns a new Collection containing the items in the specified column that are not equal to the especified value.
     *
     * @param string $column The columns to filter by.
     * @param mixed  $value  The value to compare each item.
     *
     * @return Collection A new collection.
     */
    public function whereNot($column, $value)
    {
        return $this->filter(
            function ($item) use ($column, $value) {
                if (isset($item[$column])) {
                    return $item[$column] !== $value;
                }
            }
        );
    }

    /**
     * Returns a new Collection containing the items in the specified column that are equal to the especified value(s).
     *
     * @param string $column The columns to filter by.
     * @param array  $values The values to compare each item.
     *
     * @return Collection A new collection.
     */
    public function whereIn($column, array $values = [])
    {
        return $this->filter(
            function ($item) use ($column, $values) {
                if (isset($item[$column])) {
                    return in_array($item[$column], $values);
                }
            }
        );
    }

    /**
     * Returns a new Collection containing the items in the specified column that are not equal
     * to the especified value(s).
     *
     * @param string $column The columns to filter by.
     * @param array  $values The values to compare each item.
     *
     * @return Collection A new collection.
     */
    public function whereNotIn($column, array $values = [])
    {
        return $this->filter(
            function ($item) use ($column, $values) {
                if (isset($item[$column])) {
                    return !in_array($item[$column], $values);
                }
            }
        );
    }

    /**
     * Returns a new Collection containing the items ordered by the especified column.
     *
     * @param string $column The column to order by.
     * @param string $order  The order to sort the items.
     *
     * @return Collection A new collection.
     */
    public function orderBy($column, $order = 'ASC')
    {
        return $this->sorted(function ($a, $b) use ($column, $order) {
            if (strtoupper($order) == 'ASC') {
                return $a[$column] <=> $b[$column];
            } elseif (strtoupper($order) == 'DESC') {
                return $b[$column] <=> $a[$column];
            }
        });
    }

    /**
     * Returns a new Collection grouped by the specified column.
     *
     * @param string $column The column to group by.
     *
     * @return Collection A new collection.
     */
    public function groupBy($column)
    {
        $return = [];

        foreach ($this->getArrayCopy() as $item) {
            if (isset($item[$column])) {
                $key = $item[$column];
                $return[$key] = $item;
            } else {
                $return[] = $item;
            }
        }

        return $this->make($return);
    }

    /**
     * Gets the first item of the Collection or adds and returns a new one.
     *
     * @todo Must be implemented.
     *
     * @param string $key   The key of the item.
     * @param mixed  $value The value of the item.
     *
     * @return Collection A new collection.
     */
    public function firstOrNew($key, $value)
    {
        //
    }

    /**
     * Updates an item from the Collection or adds a new one.
     *
     * @todo Must be implemented.
     *
     * @param string $key   The key of the item.
     * @param mixed  $value The value of the item.
     *
     * @return Collection A new collection.
     */
    public function updateOrNew($key, $value)
    {
        //
    }
}
