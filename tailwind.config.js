import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
      ],
    darkMode: 'selector',
    theme: {
        extend: {
            borderColor: ['focus', 'dark'],
            ringColor: ['focus', 'dark'],
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                '8xl': '88rem',
                '9xl': '96rem',
                '10xl': '104rem',
              }
        },
    },
    safelist: [
        {
          pattern: /bg-+/,
        },
      ],
    plugins: [forms],
};
