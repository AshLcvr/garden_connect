const Encore = require('@symfony/webpack-encore');



// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */


    .addEntry('app', './assets/app.js')
    .addEntry('back_boutique', './assets/back_boutique.js')
    .addEntry('flexslider', './assets/flexslider.js')
    .addEntry('leaflet', './assets/leaflet.js')

    //front
    .addEntry('homepage', './assets/homepage.js')
    .addEntry('recherche_annonce', './assets/templates/front/annonces/recherche_annonce.js')
    .addEntry('focus_annonce', './assets/templates/front/annonces/focus_annonce.js')
    .addEntry('public_boutique', './assets/templates/front/boutique/public_boutique.js')
    // a voir
    .addEntry('register', './assets/templates/front/security/register.js')
    .addEntry('login', './assets/templates/front/security/login.js')
    //
    //back_boutique
    .addEntry('index_boutique', './assets/templates/front/boutique/index_boutique.js')
    .addEntry('detail_boutique', './assets/templates/front/boutique/detail_boutique.js')
    .addEntry('edit_boutique', './assets/templates/front/boutique/edit_boutique.js')

    // Pages Erreurs, 404 & 403
    .addEntry('error404', './assets/templates/bundles/TwigBundle/Exception/error404.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;



        // const CopyPlugin = require("copy-webpack-plugin");
        //
        // module.exports = {
        //     plugins: [
        //         new CopyPlugin({
        //             patterns: [
        //                 { from: "/public/images/logo.png", to: "/build/images/" },
        //                 // { from: "other", to: "public" },
        //             ],
        //         }),
        //     ],
        // }
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
;


module.exports = Encore.getWebpackConfig();
