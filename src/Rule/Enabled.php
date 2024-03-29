<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

class Enabled implements RuleInterface
{
    public function decide(ParameterBagInterface $bag, bool $value = false): bool
    {
        return $value;
    }

    public function getName(): string
    {
        return 'enabled';
    }
}
