const path = require('path')
const DIST_PATH = path.resolve(__dirname, '../public/assets/js/')

module.exports = {
  mode: 'development',
  entry: {
    'app': './index.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      }
    ]
  },
  output: {
    filename: '[name].bundle.js',
    path: DIST_PATH
  }
}