import { defineConfig } from "vite";
import path from "node:path";
import fullReload from "vite-plugin-full-reload";

export default defineConfig(({ command }) => {
  const isDev = command === "serve";

  return {
    base: isDev ? "/" : "./",
    publicDir: false,

    build: {
      outDir: path.resolve(__dirname, "assets/dist"),
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: {
          theme: path.resolve(__dirname, "src/js/theme.js"),
          styles: path.resolve(__dirname, "src/sass/theme.scss"),
        },
      },
    },

    server: {
      port: 5173,
      strictPort: true,
      cors: {
        origin: [
          "http://stronawordpress.local",
          "https://stronawordpress.local",
          "http://localhost",
          "http://127.0.0.1"
        ],
        methods: ["GET", "HEAD", "OPTIONS"]
      }
    },

    plugins: [
      fullReload([
        "./**/*.php",
        "./acf-json/*.json"
      ])
    ]
  };
});