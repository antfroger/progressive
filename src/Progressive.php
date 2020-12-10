<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\ParameterBagInterface;
use Progressive\Rule\RuleInterface;
use Progressive\Rule\RuleStoreInterface;

class Progressive
{
    /** @var array An array of the features' configuration */
    private $features = [];

    /** @var ParameterBagInterface */
    private $context;

    /** @var RuleStoreInterface */
    private $store;

    /**
     * @param  array                 $config
     * @param  ParameterBagInterface $context
     * @param  RuleStoreInterface    $store
     */
    public function __construct(
        array $config,
        ParameterBagInterface $context,
        RuleStoreInterface $store
    ) {
        $this->validateConfig($config);

        $this->features = $config['features'];
        $this->context  = $context;
        $this->store    = $store;
    }

    /**
     * @param  string $feature
     * @return bool
     */
    public function isEnabled(string $feature): bool
    {
        if (!array_key_exists($feature, $this->features)) {
            return false;
        }

        $rules = $this->features[$feature];

        // Short syntax
        if (is_bool($rules)) {
            return $rules;
        }

        if (is_array($rules) && !empty($rules)) {
            $ruleParams = reset($rules);
            $ruleName   = key($rules);

            if ($this->store->exists($ruleName)) {
                /** @var RuleInterface|callable */
                $rule = $this->store->get($ruleName);

                if ($rule instanceof RuleInterface) {
                    return $rule->decide($this->context, $ruleParams);
                } elseif (is_callable($rule)) {
                    return $rule($this->context, $ruleParams);
                }
            }
        }

        return false;
    }

    /**
     * @param  string                 $name
     * @param  callable|RuleInterface $func
     * @return void
     */
    public function addCustomRule(string $name, $func)
    {
        $this->store->add($name, $func);
    }

    /**
     * @param  array $config
     * @return void
     */
    private function validateConfig(array $config)
    {
        if (!array_key_exists('features', $config)) {
            throw new \InvalidArgumentException('Param $config must contain the key "features"');
        }
        if (count($config) > 1) {
            throw new \InvalidArgumentException('Param $config must only contain the key "features"');
        }

        // @todo check the config
    }
}
