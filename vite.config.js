import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/dosen/theme.css', 'resources/css/filament/mahasiswa/theme.css'],
            refresh: true,
        }),
        // tailwindcss(),
    ],
});
