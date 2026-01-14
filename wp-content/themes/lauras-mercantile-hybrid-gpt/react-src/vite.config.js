import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'node:path';

export default defineConfig({
  plugins: [react()],
  base: '', // important for WordPress theme relative paths
  build: {
    outDir: path.resolve(__dirname, '../assets/dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'main.jsx'),
      },
      output: {
        entryFileNames: 'index.[hash].js',
        chunkFileNames: 'chunk.[hash].js',
        assetFileNames: 'asset.[hash][extname]'
      }
    }
  }
});
