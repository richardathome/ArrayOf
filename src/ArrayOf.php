<?php
declare(strict_types=1);

namespace Richbuilds\ArrayOf;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Throwable;
use Traversable;

/**
 * Implements a type safe array
 *
 * @implements  IteratorAggregate<int|string, mixed>
 * @implements  ArrayAccess<int|string, mixed>
 */
class ArrayOf implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var array<string|int,mixed>
     */
    private array $items = [];


    /**
     * Set up the type safe array
     *
     * @param string $class
     *
     * @param array<int|string,mixed> $values
     *
     * @throws Throwable
     */
    public function __construct(
        private readonly string $class,
        array                   $values = [])
    {
        $this->set($values);
    }


    /**
     * Sets an element or elements of the array
     *
     * @param array<int|string,mixed>|string|int $key
     *
     * @param mixed|null $value
     *
     * @return $this
     *
     * @throws Throwable
     */
    public function set(array|string|int $key, mixed $value = null): self
    {
        $original_values = $this->items;

        try {
            if (is_array($key)) {

                foreach ($key as $k => $v) {
                    $this->offsetSet($k, $v);
                }

            } else {
                $this->offsetSet($key, $value);
            }
        }
        catch (Throwable $e) {
            $this->items = $original_values;
            throw $e;
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }


    /**
     * A@inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }


    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }


    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof $this->class) {
            throw new InvalidArgumentException('expected ' . $this->class . ' got ' . get_debug_type($value));
        }

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset]= $value;
        }
    }


    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->items);
    }
}