import './bootstrap.js';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.js';

// Alpine.js utilities
import Alpine from 'alpinejs';

Alpine.data('loadingState', () => ({
    loading: false,
    show() {
        this.loading = true;
    },
    hide() {
        this.loading = false;
    }
}));

Alpine.data('toast', () => ({
    message: '',
    type: 'success',
    show: false,
    
    showToast(msg, type = 'success') {
        this.message = msg;
        this.type = type;
        this.show = true;
        
        setTimeout(() => {
            this.show = false;
        }, 3000);
    }
}));

// Initialize Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.store('auth', {
        user: null,
        init() {
            this.user = window.user;
        },
        logout() {
            this.user = null;
        }
    });
});

window.Alpine = Alpine;

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue');
        const page = pages[`./Pages/${name}.vue`];
        if (!page) {
            console.error(`Could not find page: ${name}`);
            return null;
        }
        return page();
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy);
            
        // Mount the app and add error handling
        app.config.errorHandler = (err, vm, info) => {
            console.error('Vue error:', err, info);
        };
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
