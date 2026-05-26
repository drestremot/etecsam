import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Segoe UI"', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', 'Arial', 'sans-serif'],
            },
            colors: {
                'etec-dark':   '#0c1f3f',
                'etec-main':   '#1a3a6e',
                'etec-medium': '#2563eb',
                'etec-accent': '#f5a623',
                'etec-light':  '#dbeafe',
            },
        },
    },

    plugins: [forms],
};
