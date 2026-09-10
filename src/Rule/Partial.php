<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\ParameterNotFoundException;
use Progressive\Exception\RuleNotFoundException;
use Progressive\ParameterBagInterface;

final readonly class Partial implements RuleInterface
{
    /**
     * @param mixed $rules Map of rule names to their parameters
     *
     * @throws RuleNotFoundException      if a nested rule does not exist
     * @throws ParameterNotFoundException if a required parameter is missing
     */
    public function decide(ParameterBagInterface $bag, mixed $rules = null): bool
    {
        $rules = is_array($rules) ? $rules : [];

        /** @var StoreInterface $store */
        $store = $bag->get(StoreInterface::BAG_KEY);
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
