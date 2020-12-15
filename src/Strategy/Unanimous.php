<?php

namespace Progressive\Strategy;

use Progressive\ParameterBagInterface;

class Unanimous implements StrategyInterface
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
                if (false === $store->get($name)->decide($bag, $params)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName():string
    {
        return "unanimous";
    }
}
