<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Rule\Custom;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\RuleStore;

final class RuleStoreTest extends TestCase
{
    public function testAddRuleInterface()
    {
        $store = new RuleStore();

        $rule = $this->createMock(RuleInterface::class);
        $rule->expects($this->once())
            ->method('getName')
            ->willReturn('ruleinterface');

        $store->add($rule);
        $this->assertSame($rule, $store->get('ruleinterface'));
    }

    public function testAddCustomRule()
    {
        $store = new RuleStore();

        $callable = function () {
            return true;
        };

        $store->addCustom('callable', $callable);
        $this->assertEquals(
            new Custom('callable', $callable),
            $store->get('callable')
        );
    }

    public function testSameRuleInterfaceAddedTwice()
    {
        $store = new RuleStore();

        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getName')
            ->willReturn('ruleinterface');

        $store->add($rule);

        $this->expectException(\LogicException::class);
        $store->add($rule);
    }

    public function testSameRuleAddedTwice()
    {
        $store = new RuleStore();

        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getName')
            ->willReturn('rule');

        $store->add($rule);

        $this->expectException(\LogicException::class);
        $store->addCustom('rule', function () {
            return true;
        });
    }

    public function testRuleNotExists()
    {
        $this->expectException(RuleNotFoundException::class);

        $store = new RuleStore();
        $store->addCustom('env', function () {
            return true;
        });

        $store->get('unknown-rule');
    }
}
