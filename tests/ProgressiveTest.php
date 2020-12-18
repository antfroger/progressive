<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Progressive;

final class ProgressiveTest extends TestCase
{
    /** @var array */
    private static $defaultConfigFile;

    public static function setUpBeforeClass(): void
    {
        self::$defaultConfigFile = require __DIR__.'/feature-flag.php';
    }

    public function testAFeatureThatNotExistsMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->assertSame($progressive->isEnabled('i-do-not-exist'), false);
    }

    public function testAFeatureThatNotConfiguredMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->assertSame($progressive->isEnabled('i-am-not-configured'), false);
    }

    public function testBuiltInRuleEnabled(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);

        $this->assertSame($progressive->isEnabled('enabled-short-syntax'), true);
        $this->assertSame($progressive->isEnabled('disabled-short-syntax'), false);
        $this->assertSame($progressive->isEnabled('enabled-verbose-syntax'), true);
        $this->assertSame($progressive->isEnabled('disabled-verbose-syntax'), false);
    }

    public function testCustomRulesCanBeAddedAndUsedAtRuntime(): void
    {
        $progressive = new Progressive(['features' => [
            'authorize' => ['custom-flag' => true],
            'refuse' => ['custom-flag' => false],
        ]]);

        $progressive->addCustomRule('custom-flag', function (Context $context, bool $flag) {
            return $flag;
        });

        $this->assertSame($progressive->isEnabled('authorize'), true);
        $this->assertSame($progressive->isEnabled('refuse'), false);
    }

    public function testCustomRulesCanUseContext(): void
    {
        $config = [
            'features' => [
                'everywhere-but-prod' => [
                    'runtime-env' => ['DEV', 'TEST', 'PREPROD'],
                ],
            ],
        ];
        $context = new Context(['env' => 'DEV']);

        $progressive = new Progressive($config, $context);

        $progressive->addCustomRule('runtime-env', function (Context $context, array $envs): bool {
            return in_array($context->get('env'), $envs);
        });

        $this->assertSame($progressive->isEnabled('everywhere-but-prod'), true);
    }

    public function testStrategyUnanimousWithAllRulesTrueMustReturnTrue(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame($progressive->isEnabled('strategy-unanimous-all-true'), true);
    }

    public function testStrategyUnanimousWithAtLeastOneRuleFalseMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame($progressive->isEnabled('strategy-unanimous-one-false'), false);
    }

    public function testStrategyPartialWithAtLeastOneRuleTrueMustReturnTrue(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame($progressive->isEnabled('strategy-partial-one-true'), true);
    }

    public function testStrategyPartialWithNoRulesTrueMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame($progressive->isEnabled('strategy-partial-all-false'), false);
    }

    private function addCustomRulesForStategyTests(Progressive $progressive): void
    {
        $progressive->addCustomRule('authorize', function (Context $context) {
            return true;
        });
        $progressive->addCustomRule('refuse', function (Context $context) {
            return false;
        });
        $progressive->addCustomRule('valid', function (Context $context) {
            return true;
        });
        $progressive->addCustomRule('invalid', function (Context $context) {
            return false;
        });
    }
}
