<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Progressive;

final class ContextTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testGet(string $name, $value): void
    {
        $context = new Context([$name => $value]);

        $this->assertSame($value, $context->get($name));
    }

    public function testKeyNotFound(): void
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
            ['object', new Progressive(['features' => ['feature-1' => true]])],
            ['bool', false],
            ['null', null],
        ];
    }
}
