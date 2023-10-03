const path = require('path');

module.exports = {
mode: 'development', 
  entry: './dist/auth.js', // Update this with the correct path to your entry point
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist'),
  },
};
