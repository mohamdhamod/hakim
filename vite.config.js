

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const assetUrl = process.env.VITE_ASSET_URL || '/build/';

export default defineConfig({
    base: assetUrl,
    build: {
        chunkSizeWarningLimit: 2000,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: [
                        'jquery',
                        'bootstrap',
                        'datatables.net',
                        'datatables.net-bs5',
                        'datatables.net-buttons',
                        'datatables.net-buttons-bs5',
                        'jszip',
                        'pdfmake',
                        'select2',
                    ],
                },
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
    ],
});
