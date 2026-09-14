import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  publicDir: false,
  build: { manifest: 'manifest.json', outDir: 'public/build', emptyOutDir: true, cssCodeSplit: false, rollupOptions: { input: 'resources/js/app.jsx' } },
});
