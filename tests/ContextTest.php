<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;

final class ContextTest extends TestCase
{
    /**
     * @dataProvider valueProvider
     */
    public function testConstructor(string $name, $value): void
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

    /**
     * @dataProvider valueProvider
     */
    public function testSet(string $name, $value): void
    {
        $context = new Context();
        $context->set($name, $value);
        $this->assertSame($value, $context->get($name));
    }

    public function testAdd(): void
    {
        $context = new Context();
        $context->add([
            'name'           => 'Ross',
            'parents'        => ['Judy', 'Jack'],
            'ex-wives'       => 3,
            'is-a-funny-guy' => true,
            'comments'       => null,
        ]);
        $this->assertSame('Ross', $context->get('name'));
        $this->assertSame(['Judy', 'Jack'], $context->get('parents'));
        $this->assertSame(3, $context->get('ex-wives'));
        $this->assertSame(true, $context->get('is-a-funny-guy'));
        $this->assertSame(null, $context->get('comments'));

        $context->set('name', ['firstname' => 'Ross', 'lastname' => 'Geller']);
        $this->assertSame(['firstname' => 'Ross', 'lastname' => 'Geller'], $context->get('name'));
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
