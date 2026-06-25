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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#003366',
                    50: '#E6EEF5',
                    100: '#B3CCE0',
                    200: '#80AACC',
                    300: '#4D88B8',
                    400: '#1A66A3',
                    500: '#003366',
                    600: '#002952',
                    700: '#001F3D',
                    800: '#001429',
                    900: '#000A14',
                },
                ocean: {
                    DEFAULT: '#0077B6',
                    50: '#E6F4FD',
                    100: '#B3DFF8',
                    200: '#80CAF3',
                    300: '#4DB5EE',
                    400: '#1AA0E9',
                    500: '#0077B6',
                    600: '#005F92',
                    700: '#00476D',
                    800: '#002F49',
                    900: '#001824',
                },
                gold: {
                    DEFAULT: '#D4A017',
                    50: '#FDF6E6',
                    100: '#F9E5B3',
                    200: '#F5D480',
                    300: '#F1C34D',
                    400: '#EDB21A',
                    500: '#D4A017',
                    600: '#A97D12',
                    700: '#7F5B0D',
                    800: '#543A09',
                    900: '#2A1D04',
                },
            },
        },
    },

    plugins: [forms],
};
