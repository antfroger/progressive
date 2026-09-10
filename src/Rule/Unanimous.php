<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

final readonly class Unanimous implements RuleInterface
{
    /** @param array<string, mixed> $rules Map of rule names to their parameters */
    public function decide(ParameterBagInterface $bag, array $rules = []): bool
    {
        /** @var StoreInterface $store */
        $store = $bag->get('rules');
        foreach ($rules as $name => $params) {
            if (false === $store->get($name)->decide($bag, $params)) {
                return false;
            }
        }

        return true;
    }

    public function getName(): string
    {
        return 'unanimous';
    }
}
