<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Progressive\Context;
use Progressive\Progressive;

final class UnanimousTest extends TestCase
{
    /**
     * @dataProvider provideConfig
     */
    public function testDecide(Context $context, bool $expected)
    {
        $config = [
            'features' => [
                'my-awesome-feature' => [
                    'unanimous' => [
                        'runtime-env' => ["DEV", "PREPROD"],
                        'role' => 'ADMIN'
                    ]
                ]
            ]
        ];

        $progressive = new Progressive($config, $context);
        $progressive->addCustomRule('role', function (Context $context, $role) {
            return $context->get('userRole') === $role;
        });
        $progressive->addCustomRule('runtime-env', function (Context $context, array $envs) {
            $runtimeEnv = $context->get('env');
            return in_array($runtimeEnv, $envs);
        });

        $this->assertSame($expected, $progressive->isEnabled('my-awesome-feature'));
    }

    public function provideConfig()
    {
        return [
            [
                new Context(['env' => 'DEV', 'userRole' => 'ADMIN']),
                true
            ],
            [
                new Context(['env' => 'PREPROD', 'userRole' => 'ADMIN']),
                true
            ],
            [
                new Context(['env' => 'DEV', 'userRole' => 'SUPPORT']),
                false
            ],
            [
                new Context(['env' => 'PROD', 'userRole' => 'ADMIN']),
                false
            ],
            [
                new Context(['env' => 'PROD', 'userRole' => 'SUPPORT']),
                false
            ],
        ];
    }
}
