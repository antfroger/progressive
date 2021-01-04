<?php

return [
    'features' => [
        'enabled-short-syntax' => true,
        'disabled-short-syntax' => false,
        'enabled-verbose-syntax' => [
            'enabled' => true,
        ],
        'disabled-verbose-syntax' => [
            'enabled' => false,
        ],
        'i-am-not-configured' => '',
        'i-am-misconfigured' => [
            'unknown-rule' => null,
        ],
        'strategy-unanimous-all-true' => [
            'unanimous' => [
                'authorize' => null,
                'valid' => null,
            ],
        ],
        'strategy-unanimous-one-false' => [
            'unanimous' => [
                'authorize' => null,
                'refuse' => null,
            ],
        ],
        'strategy-unanimous-misconfigured' => [
            'unanimous' => [
                'unknown-rule' => null,
                'authorize' => null,
                'valid' => null,
            ],
        ],
        'strategy-partial-one-true' => [
            'partial' => [
                'authorize' => null,
                'refuse' => null,
            ],
        ],
        'strategy-partial-all-false' => [
            'partial' => [
                'refuse' => null,
                'invalid' => null,
            ],
        ],
        'strategy-partial-misconfigured' => [
            'partial' => [
                'unknown-rule' => null,
                'refuse' => null,
                'invalid' => null,
            ],
        ],
    ],
];
