<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

/**
 * Rule used to create RuleInterface objects from custom rules.
 */
class Custom implements RuleInterface
{
    /** @var string */
    private $name;

    /** @var callable */
    private $fn;

    public function __construct(string $name, callable $fn)
    {
        $this->name = $name;
        $this->fn = $fn;
    }

    public function decide(ParameterBagInterface $bag, ...$params): bool
    {
        return call_user_func($this->fn, $bag, ...$params);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
