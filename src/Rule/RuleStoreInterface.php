<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

interface RuleStoreInterface
{
    /**
     * Adds a RuleInterface rule.
     *
     * @param RuleInterface $rule The RuleInterface object.
     * @return void
     *
     * @throws \LogicException if the rule has already been added
     */
    public function add(RuleInterface $rule):void;

    /**
     * Adds a custom rule.
     *
     * @param string   $name The rule name.
     * @param callable $rule The rule function.
     * @return void
     *
     * @throws \LogicException if the rule has already been added
     */
    public function addCustom(string $name, callable $rule):void;

    /**
     * Gets a rule.
     *
     * @param  string        The rule name.
     * @return RuleInterface The rule.
     *
     * @throws RuleNotFoundException if the rule is not defined
     */
    public function get(string $name): RuleInterface;

    /**
     * Returns whether a rule is defined.
     *
     * @param string $name The rule name
     * @return bool true if the rule name is defined, false otherwise
     */
    public function exists(string $name):bool;
}
