<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;

class Enabled implements RuleInterface
{
    public const NAME = "enabled";

    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, bool $value = false):bool
    {
        return $value;
    }
}
