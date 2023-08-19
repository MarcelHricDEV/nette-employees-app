/**
 * @see https://blog.nette.org/cs/nette-vite-pouziti-nette-s-vite-pro-rychly-lokalni-vyvoj
 * @see https://github.com/lubomirblazekcz/nette-vite
 * @author https://github.com/lubomirblazekcz, https://github.com/MarcelHricSK
 */

import {resolve} from 'path';

import {defineConfig} from "vite";

const reload = {
    name: 'reload',
    handleHotUpdate({file, server}) {
        if (!file.includes('temp') && file.endsWith(".php") || file.endsWith(".latte")) {
            server.ws.send({
                type: 'full-reload',
                path: '*',
            });
        }
    }
}

export default defineConfig({
    plugins: [reload],
    server: {
        watch: {
            usePolling: true
        },
        hmr: {
            host: 'localhost'
        }
    },
    build: {
        manifest: true,
        outDir: "www",
        emptyOutDir: false,
        rollupOptions: {
            input: ['./resources/scss/app.scss'].map(entry => resolve(process.cwd(), entry)),
        }
    }
})