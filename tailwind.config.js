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
                    50: '#eef2fc',
                    100: '#d9e1f7',
                    200: '#b2c2ee',
                    300: '#7d96dc',
                    400: '#3f63c2',
                    500: '#0A2E8C',
                    600: '#092778',
                    700: '#072163',
                    800: '#05184a',
                    900: '#031031',
                    950: '#01081a',
                },
                primary: {
                    DEFAULT: '#0A2E8C',
                    foreground: '#ffffff',
                    light: '#2552C2',
                    dark: '#051E6F',
                },
            },
        },
    },

    plugins: [forms],
};
