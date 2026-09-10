<?php

declare(strict_types=1);

namespace Progressive\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Rule\Custom;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\Store;

final class StoreTest extends TestCase
{
    public function testSameRuleAddedTwiceMustThrowAnException(): void
    {
        $store = new Store();

        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getName')
            ->willReturn('ruleinterface')
        ;

        $store->add($rule);

        $this->expectException(\LogicException::class);
        $store->add($rule);
    }

    public function testRuleNotExistsMustThrowAnException(): void
    {
        $store = new Store();
        $store->addCustom('env', function () {
            return true;
        });

        $this->expectException(RuleNotFoundException::class);
        $store->get('unknown-rule');
    }

    public function testBuiltInRulesAreAlwaysLoaded(): void
    {
        $store = new Store();

        $this->assertTrue($store->exists('enabled'));
        $this->assertTrue($store->exists('partial'));
        $this->assertTrue($store->exists('unanimous'));
    }

    public function testExtraRulesAreAppendedAfterBuiltInOnes(): void
    {
        $store = new Store([new Custom('my-rule', fn () => true)]);

        self::assertTrue($store->exists('enabled'));
        self::assertTrue($store->exists('my-rule'));
    }

    public function testEmptyExtraRulesMeansOnlyBuiltIns(): void
    {
        $store = new Store([]);

        self::assertTrue($store->exists('enabled'));
        self::assertTrue($store->exists('partial'));
        self::assertTrue($store->exists('unanimous'));
    }

    public function testExtraRuleCannotOverrideABuiltIn(): void
    {
        $this->expectException(\LogicException::class);

        new Store([new Custom('enabled', fn () => true)]);
    }
}
