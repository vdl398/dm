import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'


export default defineConfig({
    plugins: [
	    vue(),
        laravel({
            input: [
			    'resources/css/app.css', 
				'resources/css/rooms.css', 
				'resources/css/stream.css', 
				'resources/css/profile.css', 
				'resources/js/app.js', 
				'resources/js/pages/home/app.js', 
				'resources/js/pages/rooms/app.js',
				'resources/js/pages/room/app.js',
				'resources/js/pages/profile/app.js',
			],
            refresh: true,
        }),
    ],
});
