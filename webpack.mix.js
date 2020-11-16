const mix = require('laravel-mix');

mix.js("resources/js/app.js", "public/js")
    .css("resources/css/app.css", "public/css");

mix.sourceMaps(false, "source-map");

// mix.browserSync(process.env.APP_URL);
mix.postCss("resources/css/app.css", "public/css", [
    require('tailwindcss'),
]);
