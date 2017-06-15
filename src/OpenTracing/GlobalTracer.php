<?php

namespace OpenTracing;

final class GlobalTracer
{
    /**
     * @var Tracer
     */
    private static $globalTracerInstance = null;

    /**
     * SetGlobalTracer sets the [singleton] Tracer returned by globalTracer().
     * Those who use GlobalTracer (rather than directly manage a Tracer instance)
     * should call setGlobalTracer as early as possible in bootstrap, prior to
     * start a new span. Prior to calling `setGlobalTracer`, any Spans started
     * via the `startActiveSpan` (etc) globals are noops.
     *
     * @param Tracer $tracer
     * @return Tracer
     */
    public static function setGlobalTracer(Tracer $tracer)
    {
        self::$globalTracerInstance = $tracer;
    }

    /**
     * GlobalTracer returns the global singleton `Tracer` implementation.
     * Before `setGlobalTracer()` is called, the `GlobalTracer()` is a noop
     * implementation that drops all data handed to it.
     *
     * @return Tracer
     */
    public static function globalTracer()
    {
        if (self::$globalTracerInstance === null) {
            self::$globalTracerInstance = NoopTracer::create();
        }

        return self::$globalTracerInstance;
    }
}
