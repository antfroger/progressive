<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

class Partial implements RuleInterface
{
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
