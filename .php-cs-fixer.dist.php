<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
;

$config = new Config();

return $config->setRules([
    '@PhpCsFixer' => true,
    'php_unit_test_class_requires_covers' => false,
    'php_unit_internal_class' => false,
    // Keep unexisting @param (e.g. @param mixed ...$params on RuleInterface)
    'no_superfluous_phpdoc_tags' => false,
])
    ->setFinder($finder)
;
