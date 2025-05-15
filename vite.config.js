import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/sidebar.js',
                'resources/js/mask.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jquery',
        },
    },
    server: {
        // Указываем, что нужно слушать все адреса, а не только localhost
        host: '0.0.0.0',
        // Указываем домен remont в список разрешенных CORS
        cors: {
            origin: ['http://remont'],
        },
        // Для проксирования запросов к Laravel с поддержкой hot reload
        hmr: {
            host: 'localhost',
        },
    },
});
