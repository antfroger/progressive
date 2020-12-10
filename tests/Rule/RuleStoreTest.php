<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\RuleStore;

final class RuleStoreTest extends TestCase
{
    public function testAddRulesThroughConstructor()
    {
        $callable = function () {
            return true;
        };
        $ruleInterface = $this->createMock(RuleInterface::class);

        $store = new RuleStore([
            'callable' => $callable,
            'ruleinterface' => $ruleInterface
        ]);

        $this->assertSame($callable, $store->get('callable'));
        $this->assertSame($ruleInterface, $store->get('ruleinterface'));
    }

    public function testAddGetRule()
    {
        $store = new RuleStore();

        $callable = function () {
            return true;
        };
        $store->add('callable', $callable);
        $this->assertSame($callable, $store->get('callable'));

        $ruleInterface = $this->createMock(RuleInterface::class);
        $store->add('ruleinterface', $ruleInterface);
        $this->assertSame($ruleInterface, $store->get('ruleinterface'));
    }

    /**
     * @dataProvider rulesProvider
     */
    public function testRuleTypes(string $name, $rule, bool $errorExpected)
    {
        if ($errorExpected) {
            $this->expectException(\LogicException::class);
        }

        $store = new RuleStore();
        $store->add($name, $rule);

        if (!$errorExpected) {
            $this->assertSame($rule, $store->get($name));
        }
    }

    public function testRuleNotExists()
    {
        $this->expectException(RuleNotFoundException::class);

        $store = new RuleStore();
        $store->add('env', function () {
            return true;
        });
        $store->get('unknown-rule');
    }

    public function rulesProvider()
    {
        return [
            ['string', 'a-rule-cannot-be-a-string', true],
            ['object', new DummyClass(), true],
            ['int', 3, true],
            ['number', 3.14, true],
            [
                'callable',
                function () {
                },
                false
            ],
            ['rule', $this->createMock(RuleInterface::class), false],
        ];
    }
}

class DummyClass
{
    public function __toString()
    {
        return "This is a dummy class";
    }
}
