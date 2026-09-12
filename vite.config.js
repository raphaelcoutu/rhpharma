import inertia from "@inertiajs/vite";
import react from "@vitejs/plugin-react";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js", "resources/sass/app.scss"],
            refresh: true,
        }),
        inertia({
            ssr: false,
        }),
        react(),
    ],
});
