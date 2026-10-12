import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    green: '#23813F',
                    dark: '#17582B',
                    lemon: '#C8F169',
                    'lemon-hover': '#B6E552',
                    forest: '#122617',
                    surface: '#F8FAF7',
                },
            },
        },
    },

    plugins: [forms],
};
