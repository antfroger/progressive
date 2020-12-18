<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

class Enabled implements RuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, bool $value = false): bool
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'enabled';
    }
}
