/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    orange: '#F58220', // ETS2 Logo Orange
                    'orange-dark': '#C65D06',
                    'orange-light': '#FDBA74',
                },
                surface: {
                    dark: '#1F2937', // Charcoal
                    light: '#F3F4F6', // Light Gray
                }
            }
        },
    },
    plugins: [],
};
