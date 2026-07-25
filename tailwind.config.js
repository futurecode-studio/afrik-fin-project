import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                    DEFAULT: '#001a61',
                    foreground: '#ffffff',
                    light: '#0a2e8c',
                    dark: '#001551',
                },
                secondary: {
                    DEFAULT: '#ffbf00',
                    foreground: '#261a00',
                },
                // Tokens type shadcn — opaques (évite les modals/cartes transparentes)
                background: '#ffffff',
                foreground: '#131c2a',
                card: {
                    DEFAULT: '#ffffff',
                    foreground: '#131c2a',
                },
                muted: {
                    DEFAULT: '#f3f5fb',
                    foreground: '#757683',
                },
                accent: {
                    DEFAULT: '#eef2fc',
                    foreground: '#001a61',
                },
                border: '#c5c5d4',
                input: '#c5c5d4',
                ring: '#001a61',
            },
            boxShadow: {
                glass: '0 10px 40px rgba(0, 26, 97, 0.08)',
                'glass-lg': '0 24px 60px rgba(0, 26, 97, 0.14)',
                glow: '0 0 28px rgba(255, 191, 0, 0.22)',
            },
            backdropBlur: {
                glass: '18px',
            },
            keyframes: {
                'adf-rise': {
                    '0%': { opacity: '0', transform: 'translateY(18px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'adf-float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
            },
            animation: {
                'adf-rise': 'adf-rise 0.7s cubic-bezier(0.22, 1, 0.36, 1) both',
                'adf-float': 'adf-float 6s ease-in-out infinite',
            },
            transitionTimingFunction: {
                soft: 'cubic-bezier(0.22, 1, 0.36, 1)',
            },
        },
    },

    plugins: [forms, typography],
};
