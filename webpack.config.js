const path = require('path');
const moment = require('moment');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => {
  const development = argv.mode.match(/dev/);

  return {
    cache: true,
    entry: {
      bundle: './webpack.bundle.js',
      loader: './webpack.loader.js',
      vendor: './webpack.vendor.js',
    },
    output: {
      path: path.resolve(__dirname, development ? 'web/.assets' : 'web/assets/stable'),
      filename: '[name].js',
    },
    devtool: development ? 'inline-source-map' : false,
    module: {
      rules: [
        {
          test: /webpack\.loader\.js$/,
          loader: 'string-replace-loader',
          options: {
            search: '__@REVISION',
            replace: String(moment().unix()),
            flags: 'g',
          },
        },
        {
          test: /\.(css|scss)$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                sourceMap: true,
                url: false,
              },
            },
            {
              loader: 'sass-loader',
              options: {
                sassOptions: { outputStyle: 'compressed' },
                sourceMap: true,
              },
            },
          ],
        },
      ],
    },
    performance: { hints: false },
    plugins: [
      new MiniCssExtractPlugin(),
      new MomentLocalesPlugin({ localesToKeep: ['ja'] }),
    ],
    target: ['web', 'es5'],
    watchOptions: {
      ignored: ['**/node_modules', '**/tmp', '**/*.log', '**/*.php'],
      poll: 300,
    },
  };
};
