<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Config\Validator;
use Progressive\ParameterBagInterface;
use Progressive\Rule\Enabled;
use Progressive\Rule\RuleStore;
use Progressive\Rule\RuleStoreInterface;
use Progressive\Strategy\Partial;
use Progressive\Strategy\StrategyStore;
use Progressive\Strategy\StrategyStoreInterface;
use Progressive\Strategy\Unanimous;

class Progressive
{
    /** @var array An array of the features' configuration */
    private $features = [];

    /** @var ParameterBagInterface */
    private $context;

    /** @var RuleStoreInterface */
    private $rules;

    /** @var StrategyStoreInterface */
    private $strategies;

    /**
     * @param array                  $config
     * @param ParameterBagInterface  $context
     */
    public function __construct(
        array $config,
        ParameterBagInterface $context = null
    ) {
        Validator::validate($config);

        $this->features = $config['features'];
        $this->context  = $context ?: new Context();

        $this->rules = new RuleStore();
        $this->rules->add(new Enabled());
        $this->context->set('rules', $this->rules);

        $this->strategies = new StrategyStore();
        $this->strategies->add(new Partial());
        $this->strategies->add(new Unanimous());
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

        $config = $this->features[$feature];

        // Short syntax: `enabled:true`
        if (is_bool($config)) {
            return $config;
        }

        // The feature's configuration is composed of a rule or rules within a strategy
        if (is_array($config) && !empty($config)) {
            reset($config);
            $name = key($config);

            if ($this->strategies->exists($name)) {
                $strategy = $this->strategies->get($name);
                $rules    = $config[$name];
                return $strategy->decide($this->context, $rules);
            }

            // No strategy defined
            // The feature's configuration is only composed by one rule
            if ($this->rules->exists($name)) {
                $rule   = $this->rules->get($name);
                $params = $config[$name];
                return $rule->decide($this->context, $params);
            }
        }

        return false;
    }

    /**
     * @param  string   $name
     * @param  callable $func
     * @return void
     */
    public function addCustomRule(string $name, callable $func)
    {
        $this->rules->addCustom($name, $func);
    }
}
