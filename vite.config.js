import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/normalize.css',
                'resources/css/tailwind.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/create.js'
            ],
            refresh: true,
        }),
    ],
});
