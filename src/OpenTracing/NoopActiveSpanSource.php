<?php

namespace OpenTracing;

final class NoopActiveSpanSource implements ActiveSpanSource
{
    public static function create()
    {
        return new self();
    }

    public function activate(Span $span, $threads = self::THREAD_DEFAULT)
    {
    }

    public function activeSpan($thread = self::THREAD_DEFAULT)
    {
        return NoopSpan::create();
    }

    public function deactivate(Span $span)
    {
    }
}
