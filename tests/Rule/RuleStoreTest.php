<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\Store;

final class StoreTest extends TestCase
{
    public function testSameRuleAddedTwiceMustThrowAnException()
    {
        $store = new Store();

        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getName')
            ->willReturn('ruleinterface');

        $store->add($rule);

        $this->expectException(\LogicException::class);
        $store->add($rule);
    }

    public function testRuleNotExistsMustThrowAnException()
    {
        $store = new Store();
        $store->addCustom('env', function () {
            return true;
        });

        $this->expectException(RuleNotFoundException::class);
        $store->get('unknown-rule');
    }
}
