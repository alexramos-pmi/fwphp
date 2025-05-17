import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      '@scss': path.resolve(__dirname, 'resources/js/scss'),
    },
  },
  root: 'resources', // onde ficam seus arquivos .vue e main.js
  base: '/resources/', // base pública usada no script type="module"
  server: {
    host: 'localhost',
    port: 5173,
    proxy: {
      // tudo que não for .js/.css será enviado ao PHP
      '^/(?!resources|@vite|node_modules).*': {
        target: 'http://localhost', // onde roda seu PHP (ex: XAMPP, Apache)
        changeOrigin: true,
      }
    }
  },
  build: {
    outDir: '../public/dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: 'resources/js/main.js'
    }
  }
})