var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');


//Main webpack config with versioning
//----------------------------------
Encore
    .configureBabel(function (babelConfig) {
        babelConfig.plugins = [
          '@babel/proposal-object-rest-spread',
          '@babel/plugin-syntax-dynamic-import',
        ];
    })
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // .addEntry('js/app', './assets/js/app.js')
    .addStyleEntry('style', './assets/scss/style.scss')
    .autoProvidejQuery()

    // .addPlugin(
    //     new CopyWebpackPlugin([
    //         {from: 'assets/images/static', to: 'images'},
    //         {from: 'assets/svg/icons', to: 'images/icons'},
    //     ])
    // )

    .enableSassLoader()
    .enableVueLoader()

    .addRule({
      resourceQuery: /blockType=i18n/,
      type: 'javascript/auto',
      loader: '@kazupon/vue-i18n-loader',
    })
;

const mainConfigVersioned = Encore.getWebpackConfig();

module.exports = [mainConfigVersioned];

