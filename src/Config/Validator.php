<?php

declare(strict_types=1);

namespace Progressive\Config;

use Progressive\Exception\ValidateException;

class Validator
{
    /**
     * @throws \InvalidArgumentException if the configuration is not valid
     */
    public static function validate(array $config): void
    {
        // Only one root key: features
        if (!array_key_exists('features', $config)) {
            throw new ValidateException('Param $config must contain the key "features"');
        }
        if (count($config) > 1) {
            throw new ValidateException('Param $config must only contain the key "features"');
        }

        // As many features as needed
        // Each feature must have only one key: a rule or a strategy
        foreach ($config['features'] as $feature => $ruleOrStrategy) {
            if (is_countable($ruleOrStrategy) && count($ruleOrStrategy) > 1) {
                throw new ValidateException(sprintf(
                    'A feature cannot contain more than one rule or startegy. Feature "%s" contains %d',
                    $feature,
                    count($ruleOrStrategy)
                ));
            }
        }
    }
}
