<?php

declare(strict_types=1);

namespace Progressive;

class Progressive
{
    /** @var array An array of the features' configuration */
    private $features = [];

    /**
     * @param  array $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->features = $config['features'];
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

        // Short syntax of `enabled`
        if (is_bool($this->features[$feature])) {
            return $this->features[$feature];
        }

        // @todo externalize this
        // This method must not be modified
        if (array_key_exists('enabled', $this->features[$feature]) && is_bool($this->features[$feature]['enabled'])) {
            return $this->features[$feature]['enabled'];
        }

        return false;
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
