<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

interface RuleStoreInterface
{
    /**
     * Adds rule.
     *
     * @param string                 $name The rule name.
     * @param callable|RuleInterface $rule The rule function.
     * @return void
     *
     * @throws \LogicException if the rule can not be added
     */
    public function add(string $name, $rule):void;

    /**
     * Gets a rule.
     *
     * @param  string                 The rule name.
     * @return callable|RuleInterface The rule.
     *
     * @throws RuleNotFoundException if the rule is not defined
     */
    public function get(string $name);

    /**
     * Returns whether a rule is defined.
     *
     * @param string $name The rule name
     * @return bool true if the rule name is defined, false otherwise
     */
    public function exists(string $name):bool;
}
