import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from "node:url";
import { PrimeVueResolver } from "@primevue/auto-import-resolver";
import Components from "unplugin-vue-components/vite";

export default defineConfig({
    optimizeDeps: {
        noDiscovery: true,
	    include: ['yup']
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/main.js"],
            refresh: true,
        }),
        vue(),
        Components({
            resolvers: [PrimeVueResolver()],
        }),
    ],
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./resources/js/", import.meta.url)),
        },
    },
});
