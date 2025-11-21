<?php

return [

    // Default theme options (defined once globally)
    'default' => [
        [ 'label' => 'Light', 'value' => 'light' ],
        [ 'label' => 'Dark',  'value' => 'dark'  ],
    ],

    // Matrix Field Handle
    'neo-field-handle' => [

        // Block Handle => true/false (enable/disable themes)
        // Only list blocks where themes should be ENABLED
        // All other blocks are disabled by default
        'block-with-themes' => true,

        // Example: another block with themes enabled
        // 'another-block-handle' => true,

    ]

];
