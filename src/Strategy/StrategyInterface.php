<?php

namespace Progressive\Strategy;

use Progressive\ParameterBagInterface;

interface StrategyInterface
{
    /**
     * Decides whether the feature is enabled or not
     * depending on the rules that the strategy contains
     *
     * @return bool
     */
    public function decide(ParameterBagInterface $bag, array $rules):bool;

    /**
     * Returns the strategy name
     *
     * @return string
     */
    public function getName():string;
}
