<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Exception\RuleNotFoundException;
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
        $this->assertSame(false, $progressive->isEnabled('i-do-not-exist'));
    }

    public function testAFeatureThatNotConfiguredMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->assertSame(false, $progressive->isEnabled('i-am-not-configured'));
    }

    public function testBuiltInRuleEnabled(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);

        $this->assertSame(true, $progressive->isEnabled('enabled-short-syntax'));
        $this->assertSame(false, $progressive->isEnabled('disabled-short-syntax'));
        $this->assertSame(true, $progressive->isEnabled('enabled-verbose-syntax'));
        $this->assertSame(false, $progressive->isEnabled('disabled-verbose-syntax'));
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

        $this->assertSame(true, $progressive->isEnabled('authorize'));
        $this->assertSame(false, $progressive->isEnabled('refuse'));
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

        $this->assertSame(true, $progressive->isEnabled('everywhere-but-prod'));
    }

    public function testStrategyUnanimousWithAllRulesTrueMustReturnTrue(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame(true, $progressive->isEnabled('strategy-unanimous-all-true'));
    }

    public function testStrategyUnanimousWithAtLeastOneRuleFalseMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame(false, $progressive->isEnabled('strategy-unanimous-one-false'));
    }

    public function testStrategyPartialWithAtLeastOneRuleTrueMustReturnTrue(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame(true, $progressive->isEnabled('strategy-partial-one-true'));
    }

    public function testStrategyPartialWithNoRulesTrueMustReturnFalse(): void
    {
        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $this->assertSame(false, $progressive->isEnabled('strategy-partial-all-false'));
    }

    public function testARuleThatNotExistsMustThrowAnException(): void
    {
        $this->expectException(RuleNotFoundException::class);

        $progressive = new Progressive(self::$defaultConfigFile);
        $progressive->isEnabled('i-am-misconfigured');
    }

    public function testStrategyUnanimousWithMissingRulesMustThrowAnException(): void
    {
        $this->expectException(RuleNotFoundException::class);

        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $progressive->isEnabled('strategy-unanimous-misconfigured');
    }

    public function testStrategyPartialWithMissingRulesMustThrowAnException(): void
    {
        $this->expectException(RuleNotFoundException::class);

        $progressive = new Progressive(self::$defaultConfigFile);
        $this->addCustomRulesForStategyTests($progressive);
        $progressive->isEnabled('strategy-partial-misconfigured');
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
