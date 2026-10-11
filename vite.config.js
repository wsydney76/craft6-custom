import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { bunny } from 'laravel-vite-plugin/fonts';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Inter', {
                    alias: 'sans',
                    weights: [400, 500, 600, 700],
                    styles: ['normal', 'italic'],
                    subsets: ['latin'],
                    display: 'swap',
                    preload: [{ weight: 400 }, { weight: 700 }],
                    fallbacks: ['system-ui', 'sans-serif'],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        // respond to all network requests
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        // Defines the origin of the generated asset URLs during development,
        // this will also be used for the public/hot file (devserver URL)
        origin: `${process.env.DDEV_PRIMARY_URL_WITHOUT_PORT}:5173`,
        cors: {
            origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(\.ddev\.site)(?::\d+)?$/,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
