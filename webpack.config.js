const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory .deploy
    //.setManifestKeyPrefix('build/')

    // Layout
    .addEntry('app', './assets/app.js')
    .addEntry('back_boutique', './assets/back_boutique.js')
    .addEntry('admin', './assets/admin.js')

    // Modules
    .addEntry('Flexslider', './assets/modules/Flexslider.js')
    .addEntry('Formulaires', './assets/modules/Formulaires.js')
    .addEntry('Leaflet', './assets/modules/Leaflet.js')
    .addEntry('Messagerie', './assets/modules/Messagerie.js')

    // Pages front
    .addEntry('homepage', './assets/templates/front/homepage.js')
    .addEntry('recherche_annonce', './assets/templates/front/annonces/recherche_annonce.js')
    .addEntry('focus_annonce', './assets/templates/front/annonces/focus_annonce.js')
    .addEntry('public_boutique', './assets/templates/front/boutique/public_boutique.js')

    // Pages back boutique
    .addEntry('preferences_boutique', './assets/templates/front/boutique/preferences_boutique.js')
    .addEntry('avis_boutique', './assets/templates/front/boutique/avis_boutique.js')

    // Pages Erreurs, 404 & 403
    .addEntry('error404', './assets/templates/bundles/TwigBundle/Exception/error404.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

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
    });

module.exports = Encore.getWebpackConfig();