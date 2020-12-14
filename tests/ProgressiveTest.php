<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Progressive;

final class ProgressiveTest extends TestCase
{
    public function testValidConfig(): void
    {
        $this->assertInstanceOf(
            Progressive::class,
            new Progressive([
                'features' => []
            ])
        );
    }

    public function testEmptyConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Progressive([]);
    }

    public function testFeaturesKeyNotDefinedInConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Progressive([
            'my-key' => []
        ]);
    }

    public function testTwoManyKeysInConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Progressive([
            'features' => [],
            'more-keys',
        ]);
    }

    public function testFeatureNotExists(): void
    {
        $progressive = new Progressive([
            'features' => []
        ]);

        $this->assertSame($progressive->isEnabled('awesome-feature'), false);
    }

    public function testBuiltinRuleEnabled(): void
    {
        $progressive = new Progressive([
            'features' => [
                'feature-1-enabled' => true,
                'feature-2-disabled' => false,
                'feature-3-enabled' => [
                    'enabled' => true,
                ],
                'feature-4-disabled' => [
                    'enabled' => false,
                ],
            ],
        ]);

        $this->assertSame($progressive->isEnabled('feature-1-enabled'), true);
        $this->assertSame($progressive->isEnabled('feature-2-disabled'), false);
        $this->assertSame($progressive->isEnabled('feature-3-enabled'), true);
        $this->assertSame($progressive->isEnabled('feature-4-disabled'), false);
    }

    public function testCustomRule(): void
    {
        $progressive = new Progressive(
            [
                'features' => [
                    'feature-1' => [
                        'env' => ['DEV', 'PREPROD'],
                    ],
                    'feature-2' => [
                        'env' => ['PROD'],
                    ],
                    'feature-3' => [
                        'role' => 'ADMIN',
                    ],
                    'feature-4' => [
                        'role' => 'DEV',
                    ],
                    'feature-5' => [
                        'fake' => 'bla',
                    ],
                ],
            ],
            new Context(['env' => 'DEV', 'role' => 'ADMIN'])
        );

        $progressive->addCustomRule(
            'env',
            function (Context $context, array $envs) {
                return in_array($context->get('env'), $envs);
            }
        );
        $progressive->addCustomRule(
            'role',
            function (Context $context, $role) {
                return $context->get('role') === $role;
            }
        );

        $this->assertSame($progressive->isEnabled('feature-1'), true);
        $this->assertSame($progressive->isEnabled('feature-2'), false);
        $this->assertSame($progressive->isEnabled('feature-3'), true);
        $this->assertSame($progressive->isEnabled('feature-4'), false);
        $this->assertSame($progressive->isEnabled('feature-5'), false);
    }
}
