let mix = require('laravel-mix');
mix.js('resources/js/*', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
        .postCss("resources/css/app.css", "public/css", [
            require("tailwindcss"),
        ])
        .postCss("resources/css/select2.min.css", "public/css")
        .sass('node_modules/flag-icons/sass/flag-icons.scss', 'public/css')

        mix.copy('resources/png', 'public/png');
