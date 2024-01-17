import {defineConfig} from "vite";
import react from "@vitejs/plugin-react-swc";
import { viteCommonjs } from '@originjs/vite-plugin-commonjs'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [react(),viteCommonjs()],
    resolve: {
        alias: {
            src: "/src",
            pages: "/src/pages",
            data: "/src/data",
            types: "/src/types",
            context: "/src/context",
            helperComps: "/src/helperComps",
            styles: "/src/styles",
            scripts: "/src/scripts",
            api: "/src/api",
            utils: "/src/utils",
        },
    },
    server: {port: 3000},
    define: {

    },
    build: {
        outDir: './build'
      }
});
