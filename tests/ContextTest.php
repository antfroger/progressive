<?php

declare(strict_types=1);

namespace Progressive\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Exception\ParameterNotFoundException;

final class ContextTest extends TestCase
{
    #[DataProvider('valueProvider')]
    public function testAddParams(string $name, $value): void
    {
        $context = new Context([$name => $value]);
        $this->assertSame($value, $context->get($name));
    }

    public static function valueProvider(): iterable
    {
        yield 'a context value' => ['string', 'a context value'];

        yield 'array' => ['list', [1, 2, 3]];

        yield 'associative array' => [
            'friends',
            ['name' => 'Chandler', 'roommate' => 'Joey', 'friend' => 'Ross'],
        ];

        yield 'object' => ['object', new \stdClass()];

        yield 'boolean' => ['bool', false];

        yield 'nothing' => ['null', null];
    }

    public function testParamNotFoundMustThrowAnException(): void
    {
        $this->expectException(ParameterNotFoundException::class);

        $context = new Context(['feature2' => 'nice']);
        $context->get('feature1');
    }
}
