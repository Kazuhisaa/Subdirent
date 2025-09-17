import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
            
                'resources/bootstrap/css/bootstrap.min.css',
                'resources/css/app.css',
                'resources/js/app.js', 
                'resources/bootstrapjs/js/bootstrap.bundle.min.js',
                'resources/css/admin.css',
            ],
            refresh: true,
        }),
    ],
});
