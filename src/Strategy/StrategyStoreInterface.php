<?php

declare(strict_types=1);

namespace Progressive\Strategy;

use Progressive\Exception\StrategyNotFoundException;

interface StrategyStoreInterface
{
    /**
     * Adds strategy.
     *
     * @param StrategyInterface $strategy The strategy.
     * @return void
     *
     * @throws \LogicException if the strategy can not be added
     */
    public function add(StrategyInterface $strategy):void;

    /**
     * Gets a strategy.
     *
     * @param  string            The strategy name.
     * @return StrategyInterface The strategy.
     *
     * @throws StrategyNotFoundException if the strategy is not defined
     */
    public function get(string $name): StrategyInterface;

    /**
     * Returns whether a strategy is defined.
     *
     * @param string $name The strategy name
     * @return bool true if the strategy name is defined, false otherwise
     */
    public function exists(string $name):bool;
}
