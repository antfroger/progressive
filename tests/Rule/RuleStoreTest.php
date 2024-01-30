<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Rule\Custom;
use Progressive\Rule\Enabled;
use Progressive\Rule\Partial;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\Store;
use Progressive\Rule\Unanimous;

final class StoreTest extends TestCase
{
    public function testSameRuleAddedTwiceMustThrowAnException()
    {
        $store = new Store();

        $rule = $this->createMock(RuleInterface::class);
        $rule->method('getName')
            ->willReturn('ruleinterface')
        ;

        $store->add($rule);

        $this->expectException(LogicException::class);
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

    public function testList()
    {
        $store = new Store();

        $this->assertEquals(
            [
                'enabled' => new Enabled(),
                'partial' => new Partial(),
                'unanimous' => new Unanimous(),
            ],
            $store->list()
        );

        $envCallable = function (): bool {
            return true;
        };
        $store->addCustom('env', $envCallable);

        $this->assertEquals(
            [
                'enabled' => new Enabled(),
                'partial' => new Partial(),
                'unanimous' => new Unanimous(),
                'env' => new Custom('env', $envCallable),
            ],
            $store->list()
        );
    }
}
