<?php

declare(strict_types=1);

namespace Progressive\Config;

use Progressive\Exception\ValidateException;

class Validator
{
    /**
     * @param array<array-key, mixed> $config The unvalidated configuration
     *
     * @throws ValidateException if the configuration is not valid
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
        // Each feature must be either:
        // - a boolean (short enabled/disabled syntax)
        // - an array containing exactly one rule or strategy
        foreach ($config['features'] as $feature => $ruleOrStrategy) {
            if (is_bool($ruleOrStrategy)) {
                continue;
            }
            if (is_array($ruleOrStrategy) && 1 === count($ruleOrStrategy)) {
                continue;
            }

            throw new ValidateException(sprintf(
                'Feature "%s" must be a boolean or an array containing exactly one rule or strategy',
                $feature
            ));
        }
    }
}
