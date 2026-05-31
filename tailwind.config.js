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
                cream: {
                    50: '#FDFBF7',
                    100: '#F9F6EE',
                    200: '#EFEAE0',
                    300: '#E2DCD0',
                    400: '#D2C7B7',
                    700: '#7A6F5D',
                    800: '#5C5243',
                    900: '#332D24',
                },
                brown: {
                    DEFAULT: '#8B5E3C',
                    10: 'rgba(139, 94, 60, 0.1)',
                    20: 'rgba(139, 94, 60, 0.2)',
                    80: 'rgba(139, 94, 60, 0.8)',
                }
            }
        },
    },

    plugins: [forms],
};
