import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'

export default defineConfig({
  publicDir: false,
  plugins: [
    vue(),
    vuetify({ autoImport: true }), // adiciona Vuetify com suporte automático
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: 'resources/js/app.js',
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        //additionalData: `@use "vuetify/settings" with ();`, // opcional, para variáveis globais
      },
    },
  },
  server: {
    hmr: {
      host: 'localhost',
    },
  },
})