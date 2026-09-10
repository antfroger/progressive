<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

final class Store implements StoreInterface
{
    /** @var array<string, RuleInterface> */
    private array $rules = [];

    /**
     * @param array<int, RuleInterface> $extraRules Additional rules, appended to the built-in ones (always loaded)
     */
    public function __construct(array $extraRules = [])
    {
        foreach ($this->getBuiltInRules() as $rule) {
            $this->add($rule);
        }
        foreach ($extraRules as $rule) {
            $this->add($rule);
        }
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

    /** @return RuleInterface[] */
    private function getBuiltInRules(): array
    {
        return [new Enabled(), new Partial(), new Unanimous()];
    }
}
