<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Config\Validator;
use Progressive\Rule\Store;
use Progressive\Rule\StoreInterface;

class Progressive
{
    /** @var array An array of the features' configuration */
    private $features = [];

    /** @var ParameterBagInterface */
    private $context;

    /** @var StoreInterface */
    private $store;

    /**
     * @param ParameterBagInterface $context
     * @param StoreInterface        $store
     */
    public function __construct(
        array $config,
        ?ParameterBagInterface $context = null,
        ?StoreInterface $store = null
    ) {
        Validator::validate($config);

        $this->features = $config['features'];
        $this->context = $context ?: new Context();

        $this->store = $store ?: new Store();
        $this->context->set('rules', $this->store);
    }

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

        // The feature's configuration is composed of a rule
        if (is_array($config) && !empty($config)) {
            reset($config);
            $name = key($config);

            if ($this->store->exists($name)) {
                $rule = $this->store->get($name);
                $params = $config[$name];

                return $rule->decide($this->context, $params);
            }
        }

        return false;
    }

    public function addCustomRule(string $name, callable $func)
    {
        $this->store->addCustom($name, $func);
    }
}
