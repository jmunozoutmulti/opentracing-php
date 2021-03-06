<?php

namespace OpenTracing;

final class NoopSpan implements Span
{
    public static function create()
    {
        return new self();
    }

    public function operationName()
    {
        return 'noop_span';
    }

    public function context()
    {
        return null;
    }

    public function finish($finishTime = null, $logRecords = [])
    {
    }

    public function overwriteOperationName($newOperationName)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function withTag($tag)
    {
    }

    public function logFields(array $logs)
    {
    }

    public function log(array $logs)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function withBaggageItem($key, $value)
    {
    }

    public function baggageItem($key)
    {
        return null;
    }
}
