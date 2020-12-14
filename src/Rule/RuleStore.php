<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

class RuleStore implements RuleStoreInterface
{
    /** @var array<RuleInterface> */
    private $rules = [];

    /**
     * {@inheritdoc}
     */
    public function add(RuleInterface $rule):void
    {
        $ruleName = $rule->getName();
        if ($this->exists($ruleName)) {
            throw new \LogicException(sprintf(
                'Rule"%s" already added. You cannot add the same rule twice',
                $ruleName
            ));
        }

        $this->rules[$ruleName] = $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function addCustom(string $name, callable $rule):void
    {
        $this->add(new Custom($name, $rule));
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name): RuleInterface
    {
        if (!$this->exists($name)) {
            throw new RuleNotFoundException(
                sprintf('"%s" does not exist in Context', $name)
            );
        }

        return $this->rules[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $name):bool
    {
        return array_key_exists($name, $this->rules);
    }
}
