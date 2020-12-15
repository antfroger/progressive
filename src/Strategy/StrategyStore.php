<?php

declare(strict_types=1);

namespace Progressive\Strategy;

use Progressive\Exception\StrategyNotFoundException;

class StrategyStore implements StrategyStoreInterface
{
    /** @var array<StrategyInterface> */
    private $strategies = [];

    /**
     * {@inheritdoc}
     */
    public function add(StrategyInterface $strategy):void
    {
        $name = $strategy->getName();
        if ($this->exists($name)) {
            throw new \LogicException(sprintf(
                'Strategy "%s" already added. You cannot add the same strategy twice',
                $name
            ));
        }

        $this->strategies[$name] = $strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name): StrategyInterface
    {
        if (!$this->exists($name)) {
            throw new StrategyNotFoundException(
                sprintf('Strategy "%s" does not exist in Context', $name)
            );
        }

        return $this->strategies[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $name):bool
    {
        return array_key_exists($name, $this->strategies);
    }
}
