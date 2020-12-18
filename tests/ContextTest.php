<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;

final class ContextTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     *
     * @param mixed $value
     */
    public function testAddParams(string $name, $value): void
    {
        $context = new Context([$name => $value]);
        $this->assertSame($value, $context->get($name));
    }

    public function testParamNotFoundMustThrowAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $context = new Context(['feature2' => 'nice']);
        $context->get('feature1');
    }

    public function valueProvider(): array
    {
        return [
            ['string', 'a context value'],
            ['array', [1, 2, 3]],
            ['associative array', ['name' => 'Chandler', 'roomate' => 'Joey', 'friend' => 'Ross']],
            ['object', new stdClass()],
            ['bool', false],
            ['null', null],
        ];
    }
}
