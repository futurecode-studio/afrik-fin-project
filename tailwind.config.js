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
                blue: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#1937BF',
                    600: '#152e9e',
                    700: '#10247d',
                    800: '#0c1b5c',
                    900: '#08123b',
                    950: '#050a24',
                },
                primary: {
                    DEFAULT: '#1937BF',
                    foreground: '#ffffff',
                    light: '#4f6bff',
                    dark: '#10247d',
                },
            },
        },
    },

    plugins: [forms],
};
