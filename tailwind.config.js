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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'etec-dark':   '#1a2e1a',
                'etec-main':   '#2d5a27',
                'etec-medium': '#4a7c44',
                'etec-accent': '#f5a623',
                'etec-light':  '#d4edda',
            },
        },
    },

    plugins: [forms],
};
