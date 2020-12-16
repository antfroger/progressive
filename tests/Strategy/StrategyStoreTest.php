<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Exception\StrategyNotFoundException;
use Progressive\Strategy\StrategyInterface;
use Progressive\Strategy\StrategyStore;
use Progressive\Strategy\StrategyStoreInterface;

final class StrategyStoreTest extends TestCase
{
    /** @var StrategyStoreInterface */
    private $store;

    /** @var StrategyInterface */
    private $mockStrategy;

    public function setUp():void
    {
        $this->mockStrategy = $this->createMock(StrategyInterface::class);
        $this->mockStrategy->method('getName')
            ->willReturn('mock-strategy');

        $this->store = new StrategyStore();
        $this->store->add($this->mockStrategy);
    }

    public function testSameStrategyAddedTwiceMustThrowAnException():void
    {
        $this->expectException(\LogicException::class);
        $this->store->add($this->mockStrategy);
    }

    public function testRuleNotExistsMustThrowAnException():void
    {
        $this->expectException(StrategyNotFoundException::class);
        $this->store->get('unknown');
    }
}
