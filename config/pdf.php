<?php

return [
    // If true, reading must rely on pre-rendered images (no on-demand PDF->image during reading).
    'require_prerender' => true,

    // Default rendering parameters for PDF -> image
    'chunks' => 3,
    'quality' => 85,
    'dpi' => 150,

    // Used by the reader "preview-first" strategy
    'preview_quality' => 55,
    'preview_dpi' => 110,

    // Pre-render variants written by the queue Job
    'render_variants' => [
        'preview' => [
            'dpi' => 110,
            'quality' => 55,
            'format' => 'jpg',
        ],
        'full' => [
            'dpi' => 150,
            'quality' => 85,
            'format' => 'jpg',
        ],
    ],
];
