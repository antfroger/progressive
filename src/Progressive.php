<?php

declare(strict_types=1);

namespace Progressive;

use Progressive\Config\Validator;
use Progressive\Exception\RuleNotFoundException;
use Progressive\Exception\ValidateException;
use Progressive\Rule\Store;
use Progressive\Rule\StoreInterface;

final class Progressive
{
    /** @var array<string, mixed> An array of the features' configuration */
    private $features = [];

    /**
     * @param array{features: array<string, mixed>} $config
     *
     * @throws ValidateException if the configuration is not valid
     */
    public function __construct(
        array $config,
        private ParameterBagInterface $context = new Context(),
        private StoreInterface $store = new Store(),
    ) {
        Validator::validate($config);
        $this->features = $config['features'];
        $this->context->set('rules', $this->store);
    }

    /**
     * @throws RuleNotFoundException if a misconfigured feature references an unknown rule
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

        // The feature's configuration is composed of a rule
        if (is_array($config) && !empty($config)) {
            $name = array_key_first($config);

            $rule = $this->store->get($name);
            $params = $config[$name];

            return $rule->decide($this->context, $params);
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->features;
    }

    public function addCustomRule(string $name, callable $func): void
    {
        $this->store->addCustom($name, $func);
    }
}
