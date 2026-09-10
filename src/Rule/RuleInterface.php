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
     *
     * @param mixed ...$params Parameters specific to each rule implementation
     */
    public function decide(ParameterBagInterface $bag): bool;
}
