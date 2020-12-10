<?php

declare(strict_types=1);

namespace Progressive\Rule;

use Progressive\Exception\RuleNotFoundException;

class RuleStore implements RuleStoreInterface
{
    /** @var array */
    private $rules = [];

    public function __construct(array $rules = [])
    {
        foreach ($rules as $name => $rule) {
            $this->add($name, $rule);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $name, $rule):void
    {
        if (!is_callable($rule) && !$rule instanceof RuleInterface) {
            throw new \LogicException(
                sprintf('"%s" is not a callable nor a RuleInterface object', $rule)
            );
        }

        $this->rules[$name] = $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $name)
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
