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
                sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', 'Arial', 'sans-serif'],
                heading: ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
            },
            colors: {
                'etec-dark':   '#0f223f',  // Azul marinho institucional CPS
                'etec-main':   '#16325c',  // Azul institucional médio
                'etec-medium': '#1e40af',  // Azul real vibrante
                'etec-accent': '#f59e0b',  // Amarelo âmbar
                'etec-light':  '#e2e8f0',  // Cinza claro
                'etec-bg':     '#f8fafc',  // Fundo claro neutro (slate-50)
                'etec-night':  '#0b1329',  // Dark navy moderno
            },
        },
    },

    plugins: [forms],
};
