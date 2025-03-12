import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/bootstrap.scss',
                'resources/js/bootstrap.js',
            ],
            refresh: true,
        }),
    ],
});
