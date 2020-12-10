<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

interface RuleInterface
{
    /**
     * Decides whether the feature is enabled or not
     *
     * @param  ParameterBagInterface $bag
     * @return bool
     */
    public function decide(ParameterBagInterface $bag):bool;
}
