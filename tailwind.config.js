import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Segoe UI"', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', 'Arial', 'sans-serif'],
            },
            colors: {
                'etec-dark':   '#1a4d2e',  // verde floresta escuro
                'etec-main':   '#2d6a4f',  // verde floresta medio
                'etec-medium': '#3a8a60',  // verde medio (CTAs)
                'etec-accent': '#f5a623',  // amarelo âmbar (inalterado)
                'etec-light':  '#c8e6c9',  // verde claro (ícones/tags)
                'etec-bg':     '#f5f0e8',  // creme/bege (fundo claro)
                'etec-night':  '#0d2818',  // verde muito escuro (dark mode)
            },
        },
    },

    plugins: [forms],
};
