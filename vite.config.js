import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',   // para que escuche en todas las interfaces dentro del contenedor
        port: 5173,        // puerto estándar de Vite
        hmr: {
            host: 'localhost', // aquí pon la IP/host que usas para entrar a Laravel en tu navegador
        },
    },
});
