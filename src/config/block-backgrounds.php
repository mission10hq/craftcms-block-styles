<?php

return [

    // Default background options (defined once globally)
    'default' => [
        [ 'label' => 'None',        'value' => 'none'        ],
        [ 'label' => 'White',       'value' => 'white'       ],
        [ 'label' => 'Gray',        'value' => 'gray'        ],
        [ 'label' => 'Primary',     'value' => 'primary'     ],
        [ 'label' => 'Secondary',   'value' => 'secondary'   ],
    ],

    // Matrix Field Handle
    'neo-field-handle' => [

        // Block Handle => true/false (enable/disable backgrounds)
        // Only list blocks where backgrounds should be ENABLED
        // All other blocks are disabled by default
        'block-with-backgrounds' => true,

        // Example: another block with backgrounds enabled
        // 'another-block-handle' => true,

    ]

];
