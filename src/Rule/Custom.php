<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

/**
 * Rule used to create RuleInterface objects from custom rules.
 */
final readonly class Custom implements RuleInterface
{
    public function __construct(private string $name, private \Closure $fn) {}

    public function decide(ParameterBagInterface $bag, mixed $params = null): bool
    {
        return ($this->fn)($bag, $params);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
