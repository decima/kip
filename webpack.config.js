const Encore = require('@symfony/webpack-encore');
const path = require("path");

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

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. index.less) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/main.js')

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
    // enables hashed filenames (e.g. app.abc123.style)
    .enableVersioning(Encore.isProduction())

    .enableLessLoader((options) => {
        options.javascriptEnabled = true;
        options.modifyVars = {

        }
    })

    .addLoader({
        test: /\.less$/,
        use: [
            {
                loader: "style-resources-loader",
                options: {
                    patterns: [
                        path.resolve(__dirname, "./assets/style/variables.less"),
                    ]
                }
            },
        ]
    })

    .enableVueLoader()
;

let config = Encore.getWebpackConfig();

config.resolve.alias = {
    ...config.resolve.alias,
    '@': path.resolve(__dirname, './client/'),
    'pages': path.resolve(__dirname, './assets/js/pages/'),
    'components': path.resolve(__dirname, './assets/js/components/'),
    'utils': path.resolve(__dirname, './assets/js/utils.js'),
    'style': path.resolve(__dirname, './assets/style/'),
};

module.exports = config;
