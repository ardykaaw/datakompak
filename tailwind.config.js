import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#e6f1fe',
                    100: '#cce3fd',
                    200: '#99c7fb',
                    300: '#66aaf9',
                    400: '#338ef7',
                    500: '#006FEE',
                    600: '#0057be',
                    700: '#00408f',
                    800: '#002a5f',
                    900: '#001530',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
