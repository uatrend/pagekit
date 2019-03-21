var fs = require('fs');
var rollup = require('rollup');
var uglify = require('uglify-js');
var babel = require('rollup-plugin-babel');
var json = require('rollup-plugin-json');
var package = require('./package.json');
var version = process.env.VERSION || package.version;
var banner =
    "/*!\n" +
    " * vue-intl v" + version + "\n" +
    " * Released under the MIT License.\n" +
    " */\n";

rollup.rollup({
  entry: 'src/index.js',
  plugins: [
    json(),
    babel({ presets: ['es2015-rollup'] })
  ]
})
.then(function (bundle) {
  return write('dist/vue-intl.js', bundle.generate({
    format: 'umd',
    banner: banner,
    moduleName: 'VueIntl'
  }).code, bundle);
})
.then(function (bundle) {
  return write('dist/vue-intl.min.js',
    banner + '\n' + uglify.minify('dist/vue-intl.js').code,
  bundle);
})
.then(function (bundle) {
  return write('dist/vue-intl.common.js', bundle.generate({
    format: 'cjs',
    banner: banner
  }).code, bundle);
})
.catch(logError);

function write(dest, code, bundle) {
  return new Promise(function (resolve, reject) {
    fs.writeFile(dest, code, function (err) {
      if (err) return reject(err);
      console.log(blue(dest) + ' ' + getSize(code));
      resolve(bundle);
    });
  });
}

function getSize(code) {
  return (code.length / 1024).toFixed(2) + 'kb';
}

function logError(e) {
  console.log(e);
}

function blue(str) {
  return '\x1b[1m\x1b[34m' + str + '\x1b[39m\x1b[22m';
}
