<?php

namespace OpenTracingTests\Unit;

use OpenTracing\Exceptions\InvalidSpanOption;
use OpenTracing\SpanContext;
use OpenTracing\SpanOptions;
use OpenTracing\SpanReference\ChildOf;
use OpenTracing\Tag;
use PHPUnit_Framework_TestCase;

final class SpanOptionsTest extends PHPUnit_Framework_TestCase
{
    const TAG_KEY = 'key';
    const TAG_VALUE = 'value';
    const START_TIME = 1497531934;

    private $childOf;
    private $startTime;
    private $options;

    /**
     * @var SpanOptions
     */
    private $spanOptions;

    public function testCreatingSpanOptionsFailsWhenPassingIncorrectStartTime()
    {
        $this->givenInvalidStartTimeOptions();
        $this->thenAnInvalidSpanOptionIsThrown();
        $this->whenCreatingSpanOptions();
    }

    public function testCreatingSpanOptionsSuccess()
    {
        $this->givenOptions();
        $this->whenCreatingSpanOptions();
        $this->thenTheSpanOptionsAreTheExpected();
    }

    private function givenInvalidStartTimeOptions()
    {
        $this->options = [
            'start_time' => 'an_invalid_timestamp',
        ];
    }

    private function givenOptions()
    {
        $this->childOf = ChildOf::withContext(SpanContext::createAsDefault());
        $this->startTime = self::START_TIME;
        $this->options = [
            'child_of' => $this->childOf,
            'tags' => [
                self::TAG_KEY => self::TAG_VALUE,
            ],
            'start_time' => $this->startTime,
        ];
    }

    private function whenCreatingSpanOptions()
    {
        $this->spanOptions = SpanOptions::create($this->options);
    }

    private function thenTheSpanOptionsAreTheExpected()
    {
        /** @var Tag $expectedTag */
        $expectedTag = current($this->spanOptions->tags());

        $this->assertTrue($this->spanOptions->childOf()->isEqual($this->childOf));
        $this->assertTrue($expectedTag->isEqual(Tag::create(self::TAG_KEY, self::TAG_VALUE)));
        $this->assertEquals($this->startTime, self::START_TIME);
    }

    private function thenAnInvalidSpanOptionIsThrown()
    {
        $this->expectException(InvalidSpanOption::class);
    }
}
