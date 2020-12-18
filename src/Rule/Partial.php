<?php

namespace Progressive\Rule;

use Progressive\ParameterBagInterface;

class Partial implements RuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, array $rules = []):bool
    {
        /** @var StoreInterface $store */
        $store = $bag->get('rules');
        foreach ($rules as $name => $params) {
            if ($store->exists($name)) {
                if (true === $store->get($name)->decide($bag, $params)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getName():string
    {
        return "partial";
    }
}
