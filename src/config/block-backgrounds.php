<?php

return [

    // Default background options (defined once globally)
    'default' => [
        [ 'label' => 'None',        'value' => 'none'        ],
        [ 'label' => 'Gradient',       'value' => 'gradient' ],
        [ 'label' => 'Custom',       'value' => 'custom' ],
    ],

    // Matrix Field Handle
    'contentBlocks' => [

        // Block Handle => true/false (enable/disable backgrounds)
        // Only list blocks where backgrounds should be ENABLED
        // All other blocks are disabled by default
        'block-with-backgrounds' => true,

        // Example: another block with backgrounds enabled
        // 'another-block-handle' => true,

    ]

];
