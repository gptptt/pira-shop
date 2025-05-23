import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: { // Add this server configuration block
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: '127.0.0.1', // Tell the client to connect to localhost on the host
            clientPort: 5173, // Ensure this matches server.port
        },
        cors: {
            origin: 'http://pirago.workshop', // allow your app origin
            methods: ['GET', 'POST'],
            credentials: true,
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
