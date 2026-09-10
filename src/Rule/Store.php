<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

class Store implements StoreInterface
{
    /** @var array<RuleInterface> */
    private $rules = [];

    final public function __construct()
    {
        $this->load();
    }

    public function add(RuleInterface $rule): void
    {
        $ruleName = $rule->getName();
        if ($this->exists($ruleName)) {
            throw new \LogicException(sprintf(
                'Rule "%s" already added. You cannot add the same rule twice',
                $ruleName
            ));
        }

        $this->rules[$ruleName] = $rule;
    }

    public function addCustom(string $name, callable $rule): void
    {
        $this->add(new Custom($name, $rule(...)));
    }

    /**
     * @throws RuleNotFoundException when the rule doesn't exist
     */
    public function get(string $name): RuleInterface
    {
        if (!$this->exists($name)) {
            throw new RuleNotFoundException(
                sprintf('Rule "%s" does not exist in Context', $name)
            );
        }

        return $this->rules[$name];
    }

    public function exists(string $name): bool
    {
        return array_key_exists($name, $this->rules);
    }

    /**
     * Load default Rules.
     *
     * @throws \LogicException if a default rule is added twice
     */
    private function load(): void
    {
        $this->add(new Enabled());
        $this->add(new Partial());
        $this->add(new Unanimous());
    }
}
