<?php

declare(strict_types=1);

namespace Progressive\Tests;

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Progressive;

final class CustomTest extends TestCase
{
    public function testCustomRuleWithScalarBoolConfig(): void
    {
        $progressive = new Progressive(['features' => [
            'authorize' => ['custom-flag' => true],
            'refuse' => ['custom-flag' => false],
        ]]);
        $progressive->addCustomRule('custom-flag', function (Context $context, bool $flag): bool {
            return $flag;
        });

        $this->assertTrue($progressive->isEnabled('authorize'));
        $this->assertFalse($progressive->isEnabled('refuse'));
    }

    public function testCustomRuleWithScalarStringConfig(): void
    {
        $context = new Context(['env' => 'DEV']);
        $progressive = new Progressive(['features' => [
            'dev-only' => ['runtime-env' => 'DEV'],
        ]], $context);
        $progressive->addCustomRule('runtime-env', function (Context $context, string $env): bool {
            return $context->get('env') === $env;
        });

        $this->assertTrue($progressive->isEnabled('dev-only'));
    }

    public function testCustomRuleWithArrayConfig(): void
    {
        $context = new Context(['env' => 'PREPROD']);
        $progressive = new Progressive(['features' => [
            'everywhere-but-prod' => ['runtime-env' => ['DEV', 'TEST', 'PREPROD']],
        ]], $context);
        $progressive->addCustomRule('runtime-env', function (Context $context, array $envs): bool {
            return in_array($context->get('env'), $envs, true);
        });

        $this->assertTrue($progressive->isEnabled('everywhere-but-prod'));
    }
}
