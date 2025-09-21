import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


const pages = import.meta.glob('./Pages/**/*.{jsx,tsx}');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: async (name) => {

        const path = `./Pages/${name}.tsx` in pages
            ? `./Pages/${name}.tsx`
            : `./Pages/${name}.jsx`;

        const page = await pages[path]();

        if (!page) {
            throw new Error(`Page component not found at path: ${path}`);
        }


        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
