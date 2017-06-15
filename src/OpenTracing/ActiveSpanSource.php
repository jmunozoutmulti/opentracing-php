<?php

namespace OpenTracing;

/**
 * Keeps track of the current active `Span`.
 */
interface ActiveSpanSource
{
    const THREAD_DEFAULT = 'default';

    /**
     * Activates an `Span`, so that it is used as a parent when creating new spans.
     * The implementation must keep track of the active spans sequence, so
     * that previous spans can be resumed after a deactivation.
     *
     * @param Span $span
     * @param string|array $threads Use only in case of async operations. Allows you to keep
     * an span as active in different threads.
     */
    public function activate(Span $span, $threads = self::THREAD_DEFAULT);

    /**
     * Returns current active `Span` for a given thread.
     *
     * @param string $thread
     * @return Span
     */
    public function activeSpan($thread = self::THREAD_DEFAULT);

    /**
     * Deactivate the given `Span`, restoring the previous active one.
     *
     * This method must take in consideration that a `Span` may be deactivated
     * when it's not really active. In that case, the current active stack
     * must not be changed (idempotency).
     *
     * Do not confuse it with the finish concept:
     *  - $span->deactivate() -> the span is not active but still "running"
     *  - $span->finish() -> the span is finished and deactivated
     *
     * @param Span $span
     */
    public function deactivate(Span $span);
}
