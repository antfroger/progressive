<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

interface RuleInterface
{
    /**
     * Returns the rule name.
     */
    public function getName(): string;

    /**
     * Decides whether the feature is enabled or not.
     */
    public function decide(ParameterBagInterface $bag): bool;
}
