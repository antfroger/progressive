<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

interface RuleInterface
{
    /**
     * Returns the rule name
     *
     * @return string
     */
    public function getName():string;

    /**
     * Decides whether the feature is enabled or not
     *
     * @param  ParameterBagInterface $bag
     * @return bool
     */
    public function decide(ParameterBagInterface $bag):bool;
}
