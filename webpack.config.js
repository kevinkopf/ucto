const EncorePlugin = require('@symfony/webpack-encore');
const CopyPlugin = require('copy-webpack-plugin');

if (!EncorePlugin.isRuntimeEnvironmentConfigured()) {
  EncorePlugin.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}
//Main webpack config with versioning
//----------------------------------
EncorePlugin
    .configureBabel((babelConfig) => {
      babelConfig.plugins = [
        '@babel/proposal-object-rest-spread',
        '@babel/plugin-syntax-dynamic-import',
        '@babel/plugin-transform-runtime',
      ];
    })
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(false)
    .enableVersioning(false)
    .splitEntryChunks()
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('appstyle', './assets/styles/app.css')
    .addStyleEntry('style', './assets/scss/style.scss')
    .addStyleEntry('autocomplete', './assets/scss/autocomplete.scss')
    .autoProvidejQuery()
    .addPlugin(
        new CopyPlugin({
          patterns: [
            {from: 'assets/images', to: 'images'},
          ]
        })
    )
    .enableSassLoader()
    .enableVueLoader(
        () => {
        },
        {runtimeCompilerBuild: true},
    )
    .enableSingleRuntimeChunk()
;

const mainConfigVersioned = EncorePlugin.getWebpackConfig();

module.exports = [mainConfigVersioned];

