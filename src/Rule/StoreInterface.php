<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

interface StoreInterface
{
    /**
     * @internal Reserved key used by Progressive — do not use this name for your own parameters.
     * Key under which the store is registered in the user's Context
     */
    public const BAG_KEY = 'rules';

    /**
     * Adds a RuleInterface rule.
     *
     * @param RuleInterface $rule the RuleInterface object
     *
     * @throws \LogicException if the rule has already been added
     */
    public function add(RuleInterface $rule): void;

    /**
     * Adds a custom rule.
     *
     * @param string   $name the rule name
     * @param callable $rule the rule function
     *
     * @throws \LogicException if the rule has already been added
     */
    public function addCustom(string $name, callable $rule): void;

    /**
     * Gets a rule.
     *
     * @param string $name the rule name
     *
     * @return RuleInterface the rule
     *
     * @throws RuleNotFoundException if the rule is not defined
     */
    public function get(string $name): RuleInterface;

    /**
     * Returns whether a rule is defined.
     *
     * @param string $name The rule name
     *
     * @return bool true if the rule name is defined, false otherwise
     */
    public function exists(string $name): bool;
}
