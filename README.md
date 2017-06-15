# OpenTracing API for PHP

[![Build Status](https://travis-ci.org/jcchavezs/opentracing-php.svg?branch=master)](https://travis-ci.org/jcchavezs/opentracing-php) [![OpenTracing Badge](https://img.shields.io/badge/OpenTracing-enabled-blue.svg)](http://opentracing.io)

PHP library for the OpenTracing's API.

## Required Reading

In order to understand the library, one must first be familiar with the
[OpenTracing project](http://opentracing.io) and
[specification](http://opentracing.io/documentation/pages/spec.html) more specifically.

## API overview for those adding instrumentation

When consuming this library one really only need to worry
about a couple of key abstractions: the `Tracer::startSpan` method, the `Span`
interface, and binding a `Tracer` at bootstrap time. Here are code snippets
demonstrating some important use cases:

#### Singleton initialization

The simplest starting point is to set the global tracer. As early as possible, do:

```php
    use OpenTracing\GlobalTracer;
    use OtherOpenTracingImplementation\Tracer;
    
    GlobalTracer::setGlobalTracer(Tracer::create());
```

Note that the `GlobalTracer` is a singleton itself but all the rest public methods are
`static` so it can be perfectly injected on any DI approach.

#### Creating a Span given an existing Request

To start a new `Span`, you can use the `startActiveSpan` method.

```php
    use Psr\Http\Message\RequestInterface;

    ...

    $spanContext = GlobalTracer::globalTracer()->extract(
        Propagator::HTTP_HEADERS,
        HttpHeaders::withHeaders($request->getHeaders())
    );
    
    function doSomething(SpanContext $spanContext, ...) {
        ...
        
        $span = GlobalTracer::globalTracer()->startManualSpan('my_span', ['child_of' => $spanContext]);
        
        ...
        
        $span->log([
            'event' => 'soft error',
            'type' => 'cache timeout',
            'waiter.millis' => 1500,
        ])
        
        $span->finish();
    }
```

#### Starting an empty trace by creating a "root span"

It's always possible to create a "root" `Span` with no parent or other causal reference.

```php
    $span = $tracer->startActiveSpan('my_first_span');
    ...
    $span->finish();
```

#### Creating a child span assigning parent manually

```php
	use OpenTracing\SpanReference\ChildOf;
	
	$parent = GlobalTracer::globalTracer()->startManualSpan('parent');	$child = GlobalTracer::globalTracer()->startManualSpan('child', [
		'child_of' => $parent
	]);
	...
	$child->finish();
	...
	$parent->finish();
```

#### Creating a child span using automatic active span management
Every new span will take the active span as parent and it will take its spot.

```php    
	$parent = GlobalTracer::globalTracer()->startManualSpan('parent');        ...

    // Since the parent span has been created by using startActiveSpan we don't need
    // to pass a reference for this child span
    $child = GlobalTracer::globalTracer()->startActiveSpan('my_second_span');
    ... 
    $child->finish();
    ...
    $parent->finish();
```

#### Serializing to the wire

```php
    use OpenTracing\Carriers\HttpHeaders as HttpHeadersCarrier;
    use OpenTracing\Context;
    use OpenTracing\GlobalTracer;
    
    ...
    
    $tracer = GlobalTracer::globalTracer(); 
    
    $spanContext = $tracer->extract(
        Propagator::HTTP_HEADERS,
        HttpHeaders::withHeaders($request->getHeaders())
    );
    
    try {
        $span = $tracer->startManualSpan('my_span', ['child_of' => $spanContext]);

        $client = new GuzzleHttp\Client;
        
        $request = new \GuzzleHttp\Psr7\Request('GET', 'http://myservice');
        
        $tracer->inject(
            $span->context(),
            Propagator::HTTP_HEADERS,
            HttpHeadersCarrier::withHeaders($request->getHeaders())
        )

        $client->send($request);
        ...
    } catch (Exception $e) {
        ...
    }
    ...        
```

#### Deserializing from the wire

When using http header for context propagation you can use the `Request` for example:

```php
    use OpenTracing\Carriers\HttpHeaders;
    use OpenTracing\SpanReference\ChildOf;
    use OpenTracing\GlobalTracer;
    
    $request = Request::createFromGlobals();
    $tracer = GlobalTracer::globalTracer();
    $spanContext = $tracer->extract(Propagator::HTTP_HEADERS, HttpHeaders::fromRequest($request));
    $tracer->startSpan('my_span', ChildOf::withContext($spanContext)); 
```
