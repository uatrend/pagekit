/**
 * Popular Tasks
 * -------------
 *
 * compile: compiles the .less files of the specified packages
 * lint: runs jshint on all .js files
 */

var merge = require('merge-stream'),
    gulp = require('gulp'),
    header = require('gulp-header'),
    less = require('gulp-less'),
    rename = require('gulp-rename'),
    eslint = require('gulp-eslint'),
    plumber = require('gulp-plumber'),
    fs = require('fs'),
    path = require('path');

// paths of the packages for the compile-task
var pkgs = [
    {path: 'app/installer/', data: '../../composer.json'},
    {path: 'app/system/modules/theme/', data: '../../../../composer.json'}
];

// banner for the css files
var banner = "/*! <%= data.title %> <%= data.version %> | (c) 2014 Pagekit | MIT License */\n";

var cldr = {
    cldr: path.join(__dirname, 'node_modules/cldr-core/supplemental/'),
    intl: path.join(__dirname, 'app/system/modules/intl/data/'),
    locales: path.join(__dirname, 'node_modules/cldr-localenames-modern/main/'),
    formats: path.join(__dirname, 'app/assets/vue-intl/dist/locales/'),
    languages: path.join(__dirname, 'app/system/languages/')
};

// general error handler for plumber
var errhandler = function (error) {
    this.emit('end');
    return console.error(error.toString());
};

/**
 * Copy required asset files to app/assets/** folder
 */
gulp.task('copy', function() {
    // Uikit
    gulp.src([
        './node_modules/uikit/dist/js/uikit.js',
        './node_modules/uikit/dist/js/uikit.min.js',
        './node_modules/uikit/dist/js/uikit-icons.js',
        './node_modules/uikit/dist/js/uikit-icons.min.js'])
        .pipe(gulp.dest('./app/assets/uikit/dist/js/'));
    // Vue
    gulp.src(['./node_modules/vue/dist/vue.js', './node_modules/vue/dist/vue.min.js'])
        .pipe(gulp.dest('./app/assets/vue/dist/'));
    // Lodash
    gulp.src(['./node_modules/lodash/lodash.js', './node_modules/lodash/lodash.min.js'])
        .pipe(gulp.dest('./app/assets/lodash/dist/'));
    // Flatpickr
    gulp.src([
        './node_modules/flatpickr/dist/flatpickr.css',
        './node_modules/flatpickr/dist/flatpickr.min.css',
        './node_modules/flatpickr/dist/flatpickr.js',
        './node_modules/flatpickr/dist/flatpickr.min.js'])
        .pipe(gulp.dest('./app/assets/flatpickr/dist/'));
    gulp.src('./node_modules/flatpickr/dist/l10n/*.js')
        .pipe(gulp.dest('./app/assets/flatpickr/dist/l10n/'));
});

/**
 * Compile all less files
 */
gulp.task('compile', function () {

    pkgs = pkgs.filter(function (pkg) {
        return fs.existsSync(pkg.path);
    });

    return merge.apply(null, pkgs.map(function (pkg) {
        data = require('./' + pkg.path + pkg.data);
        return gulp.src(pkg.path + '**/less/*.less', {base: pkg.path})
            .pipe(plumber(errhandler))
            .pipe(less({compress: true, relativeUrls: true}))
            .pipe(header(banner, {data: require('./' + pkg.path + pkg.data)}))
            .pipe(rename(function (file) {
                // the compiled less file should be stored in the css/ folder instead of the less/ folder
                file.dirname = file.dirname.replace('less', 'css');
            }))
            .pipe(gulp.dest(pkg.path));
    }));

});

/**
 * Watch for changes in files
 */
gulp.task('watch', function (cb) {
    gulp.watch([
        './app/installer/**/*.less',
        './app/system/**/*.less'
        ], gulp.series('compile'));

});

/**
 * Lint all script files
 */
gulp.task('lint', function () {
    return gulp.src([
        'app/modules/**/*.js',
        'app/system/**/*.js',
        'extensions/**/*.js',
        'themes/**/*.js',
        'app/modules/**/*.vue',
        'app/system/**/*.vue',
        'extensions/**/*.vue',
        'themes/**/*.vue',
        '!**/bundle/*',
        '!**/vendor/**/*',
        '!**/assets/**/*',
        '!node_modules/**',
        '!.git/**'
    ])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failOnError());
});

gulp.task('cldr', function (done) {

    // territoryContainment
    var data = {}, json = JSON.parse(fs.readFileSync(cldr.cldr + 'territoryContainment.json', 'utf8')).supplemental.territoryContainment;
    Object.keys(json).forEach(function (key) {
        if (isNaN(key)) return;
        data[key] = json[key]._contains;
    });
    fs.writeFileSync(cldr.intl + 'territoryContainment.json', JSON.stringify(data));

    fs.readdirSync(cldr.languages)
        .filter(function (file) {
            return fs.statSync(path.join(cldr.languages, file)).isDirectory();
        })
        .forEach(function (src) {

            var id = src.replace('_', '-'), shortId = id.substr(0, id.indexOf('-')), found;

            ['languages', 'territories'].forEach(function (name) {

                found = false;
                [id, shortId, 'en'].forEach(function (locale) {
                    var file = cldr.locales + locale + '/' + name + '.json';
                    if (!found && fs.existsSync(file)) {
                        found = true;
                        fs.writeFileSync(cldr.languages + src + '/' + name + '.json', JSON.stringify(JSON.parse(fs.readFileSync(file, 'utf8')).main[locale].localeDisplayNames[name]));
                    }
                });

            });

            found = false;
            [id.toLowerCase(), shortId, 'en'].forEach(function (locale) {
                var file = cldr.formats + locale + '.json';
                if (!found && fs.existsSync(file)) {
                    found = true;
                    fs.writeFileSync(cldr.languages + src + '/formats.json', fs.readFileSync(file, 'utf8'));
                }
            });

        });

    done();
});

gulp.task('default', gulp.parallel('copy', 'compile'));
