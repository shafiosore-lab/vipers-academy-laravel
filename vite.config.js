import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // The public CBO website is the only frontend in this project.
            // Legacy SaaS bundles (app.css, gamesuite.css, tournament-*.js)
            // were removed with the dashboard and are intentionally not listed.
            input: [
                'resources/css/site.css',
                'resources/js/site.js',
            ],
            refresh: true,
        }),
    ],
});
