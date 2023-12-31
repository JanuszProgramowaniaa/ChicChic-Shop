const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')
    .addEntry('account', './assets/styles/account/style.scss')
    .addEntry('account_order_details', './assets/styles/account/orderDetails.scss')
    .addEntry('about', './assets/styles/aboutUs/style.scss')
    .addEntry('index', './assets/styles/index/index.scss')    
    .addEntry('summary', './assets/styles/cart/summary.scss')    
    

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    .enableSassLoader()

    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();