import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        // Generar nombres de archivos con hash para evitar problemas de caché
        rollupOptions: {
            output: {
                manualChunks: undefined,
            }
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
