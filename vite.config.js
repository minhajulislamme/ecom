import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/js/app.js', 
                'resources/css/backend/app.css', 
                'resources/js/backend/app.js',
                'resources/js/frontend/app.css',
                'resources/js/frontend/app.js'],
            refresh: true,
        }),
    ],
});
