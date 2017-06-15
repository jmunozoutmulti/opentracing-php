<?php

namespace OpenTracing;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\SpanReference\ChildOf;

final class SpanOptions
{
    /**
     * @var ChildOf
     */
    private $childOf;

    /**
     * @var Tag[]
     */
    private $tags = [];

    /**
     * @var int|float|\DateTimeInterface
     */
    private $startTime;

    public static function create(array $options)
    {
        $spanOptions = new self();
        
        foreach ($options as $key => $value) {
            switch ($key) {
                case 'child_of':
                    $spanOptions->childOf = self::buildChildOf($value);
                    break;

                case 'tags':
                    foreach ($value as $tag => $tagValue) {
                        $spanOptions->tags[] = Tag::create($tag, $tagValue);
                    }
                    break;

                case 'start_time':
                    if (is_scalar($value) && !is_numeric($value)) {
                        throw InvalidSpanOption::create($key);
                    }

                    $spanOptions->startTime = $value;
                    break;

                default:
                    throw InvalidSpanOption::create($key);
                    break;
            }
        }

        return $spanOptions;
    }

    private static function buildChildOf($value)
    {
        if ($value instanceof Span) {
            return ChildOf::withContext($value->context());
        } elseif ($value instanceof SpanContext) {
            return ChildOf::withContext($value);
        } elseif ($value instanceof ChildOf) {
            return $value;
        }

        throw InvalidSpanOption::create('child_of');
    }

    /**
     * @return ChildOf
     */
    public function childOf()
    {
        return $this->childOf;
    }

    /**
     * @return Tag[]
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * @return int|float|\DateTimeInterface
     */
    public function startTime()
    {
        return $this->startTime;
    }
}
