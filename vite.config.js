import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/landing/app.css",
                "resources/js/landing/app.js",
            ],
            refresh: [
                "resources/views/**",
                "app/Livewire/**",
                "app/Filament/**",
            ],
        }),
    ],

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
            "~": path.resolve(__dirname, "resources"),
        },
    },

    server: {
        host: "localhost",
        port: 5173,
        strictPort: true,
    },
});
