<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

final readonly class Enabled implements RuleInterface
{
    /** @param bool $value Whether the rule is enabled or not. */
    public function decide(ParameterBagInterface $bag, bool $value = false): bool
    {
        return $value;
    }

    public function getName(): string
    {
        return 'enabled';
    }
}
