const mix = require('laravel-mix');

let buildPath = "public/js";

mix.js([
    'resources/js/app.js',
], `${buildPath}/app.js`).vue({
    version: 3
});

mix.sass('resources/css/app.scss', `public/css/main.css`);

mix.version();
