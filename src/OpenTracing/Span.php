<?php

namespace OpenTracing;

interface Span
{
    /**
     * @return string
     */
    public function operationName();

    /**
     * @return SpanContext
     */
    public function context();

    /**
     * @param float|int|\DateTimeInterface|null $finishTime if passing float or int
     * it should represent the timestamp (including as many decimal places as you need)
     * @param array $logRecords
     * @return mixed
     */
    public function finish($finishTime = null, $logRecords = []);

    /**
     * @param string $newOperationName
     */
    public function overwriteOperationName($newOperationName);

    /**
     * Sets a tag to the Span.
     * As an implementor consider supporting a single Tag object or a $tag, $tagValue.
     *
     * @param Tag|string $tag
     * @return mixed
     */
    public function withTag($tag/** [, $tagValue] **/);

    /**
     * @param array, LogField[]|string[string] $logs
     */
    public function logFields(array $logs);

    /**
     * log is a concise, readable way to record key:value logging data about a Span.
     * As an implementor, consider to use this one LogUtils\keyValueLogFieldsConverter
     *
     * @param array|string[string] $logs
     */
    public function log(array $logs);

    /**
     * @param string $key
     * @param string $value
     * @return Span
     */
    public function withBaggageItem($key, $value);

    /**
     * @param string $key
     * @return string
     */
    public function baggageItem($key);
}
