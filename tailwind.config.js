const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: {
        content: ['./resources/views/**/*.blade.php']
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [require('@tailwindcss/ui')],
};
