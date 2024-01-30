<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

class Unanimous implements RuleInterface
{
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
