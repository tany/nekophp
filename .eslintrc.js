/* eslint quote-props: ["error", "always"] */
module.exports = {
  'env': {
    'browser': true,
    'jquery': true,
  },
  'extends': [
    'airbnb-base',
  ],
  'globals': {
    '$_': true,
    'bootstrap': true,
    'Cookies': true,
    'core': true,
  },
  'ignorePatterns': [
    '**/stable/**/*.js',
    '**/vendor/**/*.js',
  ],
  'parser': 'babel-eslint',
  'parserOptions': {
    'ecmaVersion': 2021,
    'sourceType': 'module',
  },
  'rules': {
    'arrow-parens': ['error', 'as-needed'],
    'consistent-return': 'off',
    'indent': ['error', 2],
    'no-cond-assign': 'off',
    'no-console': ['error', { 'allow': ['debug', 'error'] }],
    'no-new': 'off',
    'no-param-reassign': 'off',
    'no-underscore-dangle': ['error', { 'allow': ['$_'] }],
    'no-unsafe-optional-chaining': 'off',
    'object-curly-newline': ['error', { 'multiline': true }],
    'padded-blocks': 'off',
    'prefer-object-spread': 'off',
  },
};
