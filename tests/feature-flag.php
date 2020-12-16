<?php

return [
    'features' => [
        'enabled-short-syntax' => true,
        'disabled-short-syntax' => false,
        'enabled-verbose-syntax' => [
            'enabled' => true
        ],
        'disabled-verbose-syntax' => [
            'enabled' => false
        ],
        'i-am-not-configured' => '',
        'strategy-unanimous-all-true' => [
            'unanimous' => [
                'authorize' => null,
                'valid' => null,
            ]
        ],
        'strategy-unanimous-one-false' => [
            'unanimous' => [
                'authorize' => null,
                'refuse' => null,
            ]
        ],
        'strategy-partial-one-true' => [
            'partial' => [
                'authorize' => null,
                'refuse' => null,
            ]
        ],
        'strategy-partial-all-false' => [
            'partial' => [
                'refuse' => null,
                'invalid' => null,
            ]
        ],
    ]
];
