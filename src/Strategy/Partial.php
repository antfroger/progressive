<?php

namespace Progressive\Strategy;

use Progressive\Context;
use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\RuleStoreInterface;

class Partial implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function decide(ParameterBagInterface $bag, array $rules):bool
    {
        /** @var RuleStoreInterface $store */
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
