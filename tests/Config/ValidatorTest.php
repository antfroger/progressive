<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Config\Validator;
use Progressive\Exception\ValidateException;

final class ValidatorTest extends TestCase
{
    public function testEmptyConfigMustThrowAnException():void
    {
        $this->expectException(ValidateException::class);
        Validator::validate([]);
    }

    public function testInvalidRootKeyInConfigMustThrowAnException():void
    {
        $this->expectException(ValidateException::class);
        Validator::validate([
            'my-key' => []
        ]);
    }

    public function testTwoManyRootKeyInConfigMustThrowAnException():void
    {
        $this->expectException(ValidateException::class);
        Validator::validate([
            'features' => [],
            'more-keys',
        ]);
    }

    public function testFeatureWithMoreThanOneRuleMustThrowAnException():void
    {
        $this->expectException(ValidateException::class);
        Validator::validate([
            'features' => [
                'an-awesome-feature' => true,
                'feature-with-built-in-rule' => [
                    'enabled' => false,
                ],
                'feature-with-custom-rule' => [
                    'runtime-env' => ['DEV', 'TEST'],
                ],
                'feature-with-anonimous-strategy' => [
                    'unanimous' => [
                        'rule1' => ['param1', 'param2'],
                        'rule2' => 'param',
                    ],
                ],
                'an-unconfigured-feature' => '',
                'invalid-feature' => [
                    'rule1' => ['param1', 'param2'],
                    'rule2' => 'param',
                ],
            ],
        ]);
    }

    public function testConfigIsValid():void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate([
            'features' => []
        ]);

        Validator::validate([
            'features' => [
                'an-awesome-feature' => true,
                'feature-with-built-in-rule' => [
                    'enabled' => false,
                ],
                'feature-with-custom-rule' => [
                    'runtime-env' => ['DEV', 'TEST'],
                ],
                'feature-with-anonimous-strategy' => [
                    'unanimous' => [
                        'rule1' => ['param1', 'param2'],
                        'rule2' => 'param',
                    ],
                ],
                'feature-with-partial-strategy' => [
                    'partial' => [
                        'rule1' => ['param1', 'param2'],
                        'rule2' => 'param',
                    ],
                ],
                'an-unconfigured-feature' => '',
            ],
        ]);
    }
}
