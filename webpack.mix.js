let mix = require('laravel-mix');
let path = require('path');
let webpack = require('webpack');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix
    // APP
    .js('resources/assets/js/app/index.js', 'public/js/app.js')
    .sass('resources/assets/sass/app/index.scss', 'public/css/app.css')

    // ADMIN
    .js('resources/assets/js/admin/index.js', 'public/js/admin.js')
    .sass('resources/assets/sass/admin/index.scss', 'public/css/admin.css')

    .extract(['vue', 'jquery', 'moment', 'axios', 'lodash'])

    // Medium editor Admin
    .copy('data/js/medium-editor.js', 'public/js')

    // Copy Favicons
    .copy('data/favicons/apple-touch-icon.png', 'public/favicons')
    .copy('data/favicons/favicon.png', 'public/favicons')
    .copy('data/favicons/favicon-16x16.png', 'public/favicons')
    .copy('data/favicons/favicon-32x32.png', 'public/favicons')
    .copy('data/favicons/favicon-48x48.png', 'public/favicons')
    .copy('data/favicons/mstile-150x150.png', 'public/favicons')
    .copy('data/favicons/safari-pinned-tab.svg', 'public/favicons')
    .copy('data/favicons/android-chrome-192x192.png', 'public/favicons')
    .copy('data/favicons/android-chrome-512x512.png', 'public/favicons')
    .copy('data/favicons/manifest.json', 'public/favicons')

    .copy('data/defaults/**/*', 'public/site/defaults')

    // Copy msaplication config
    .copy('data/favicons/browserconfig.xml', 'public/favicons')


    .sourceMaps()
    .version()
    .then((stats) => {

        let manifest = require('./public/mix-manifest.json')

        let androidChrome192, androidChrome512

        let assets = stats.compilation.assets

        for (const key in assets) {

            if (key == '/favicons/android-chrome-192x192.png') {
                androidChrome192 = manifest[key]
            }

            if (key == '/favicons/android-chrome-512x512.png') {
                androidChrome512 = manifest[key]
            }
        }

        let data = {
            "name": "Laravel Plus",
            "icons": [
                {
                    "src": androidChrome192,
                    "sizes": "192x192",
                    "type": "image/png"
                },
                {
                    "src": androidChrome512,
                    "sizes": "512x512",
                    "type": "image/png"
                }
            ],
            "theme_color": "#E83E8C",
            "display": "standalone"
        }

        let fs = require('fs');
        let fileName = path.join(__dirname, '/public/favicons/manifest.json');

        fs.stat(fileName, (err, fileStats) => {

            fs.open(fileName, "w+", (err, fd) => {

                fs.writeSync(fd, JSON.stringify(data))

            })

        })

    })
    .browserSync({
        proxy: 'laravelaps.test',
        host: 'laravelaps.test',
        notify: true,
        port: 8000
    })

