<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

final readonly class Partial implements RuleInterface
{
    /** @param array<string, mixed> $rules Map of rule names to their parameters */
    public function decide(ParameterBagInterface $bag, array $rules = []): bool
    {
        /** @var StoreInterface $store */
        $store = $bag->get('rules');
        foreach ($rules as $name => $params) {
            if (true === $store->get($name)->decide($bag, $params)) {
                return true;
            }
        }

        return false;
    }

    public function getName(): string
    {
        return 'partial';
    }
}
