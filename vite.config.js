import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

export default defineConfig(({ mode }) => ({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      '@scss': path.resolve(__dirname, 'resources/js/scss'),
    },
  },
  root: 'resources', // ponto de entrada para o Vite
  base: mode === 'production' ? '/dist/' : '/resources/', // base correta por ambiente
  server: {
    host: 'localhost',
    port: 5173,
    proxy: {
      '^/(?!resources|@vite|node_modules).*': {
        target: 'http://localhost',
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
}))