<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidTagValue;

final class Tag
{
    private $key;
    private $value;

    private function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public static function create($key, $value)
    {
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return new self($key, $value);
            }

            throw InvalidTagValue::notStringable($value);
        }

        if (!is_scalar($value)) {
            throw InvalidTagValue::notScalar($value);
        }

        return new self($key, $value);
    }

    public function key()
    {
        return $this->key;
    }

    public function value()
    {
        return $this->value;
    }

    public function is($key)
    {
        return $this->key == $key;
    }

    public function isEqual(Tag $tag)
    {
        return
            $this->key === $tag->key() &&
            $this->value === $tag->value();
    }
}
