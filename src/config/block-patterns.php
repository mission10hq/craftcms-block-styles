<?php

return [

    // Default pattern options (defined once globally)
    'default' => [
        [ 'label' => 'None',   'value' => 'none'   ],
        [ 'label' => 'Dots',   'value' => 'dots'   ],
        [ 'label' => 'Lines',  'value' => 'lines'  ],
        [ 'label' => 'Grid',   'value' => 'grid'   ],
    ],

    // Matrix Field Handle
    'neo-field-handle' => [

        // Block Handle => true/false (enable/disable patterns)
        // Only list blocks where patterns should be ENABLED
        // All other blocks are disabled by default
        'block-with-patterns' => true,

        // Example: another block with patterns enabled
        // 'another-block-handle' => true,

    ]

];
