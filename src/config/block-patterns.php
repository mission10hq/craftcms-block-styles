<?php

return [

    // Default pattern options (defined once globally)
    'default' => [
        [ 'label' => 'None',   'value' => 'none'   ],
        [ 'label' => 'Wave',   'value' => 'wave'   ],
    ],

    // Matrix Field Handle
    'contentBlocks' => [

        // Block Handle => true/false (enable/disable patterns)
        // Only list blocks where patterns should be ENABLED
        // All other blocks are disabled by default
        'block-with-patterns' => true,

        // Example: another block with patterns enabled
        // 'another-block-handle' => true,

    ]

];
