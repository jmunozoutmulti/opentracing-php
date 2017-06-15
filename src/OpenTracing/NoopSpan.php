<?php

namespace OpenTracing;

final class NoopSpan implements Span
{
    public static function create()
    {
        return new self();
    }

    /**
     * {@inheritdoc}
     */
    public function operationName()
    {
        return 'noop_span';
    }

    /**
     * {@inheritdoc}
     */
    public function context()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function finish($finishTime = null, $logRecords = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function overwriteOperationName($newOperationName)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setTag($tag)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function logFields(array $logs)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function log(array $logs)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setBaggageItem($key, $value)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function baggageItem($key)
    {
        return null;
    }
}
