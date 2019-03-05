// webpack.config.js
let Encore = require('@symfony/webpack-encore');
let Env    = require('./webpack.env');

Encore
// the project directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath(Env.publicPath)

    .setManifestKeyPrefix('build/')

    // will create web/build/app.js and web/build/app.css
    .addEntry('app', './app/Resources/public/js/app.js')

    // this creates a 'vendor.js' file with jquery and the bootstrap JS module
    // these modules will *not* be included in page1.js or page2.js anymore
    .createSharedEntry('vendor', [
        'jquery',
        'bootstrap'
    ])

    // allow legacy applications to use $/jQuery as a global variable
    //.autoProvidejQuery()

    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // show OS notifications when builds finish/fail
    .enableBuildNotifications()

    // create hashed filenames (e.g. app.abc123.css)
    .enableVersioning()

    // allow sass/scss files to be processed
    .enableSassLoader()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();