/**
 * Popular Tasks
 * -------------
 *
 * compile: compiles the .less files of the specified packages
 * lint: runs jshint on all .js files
 */

const merge = require('merge-stream');
const gulp = require('gulp');
const header = require('gulp-header');
const less = require('gulp-less');
const rename = require('gulp-rename');
const eslint = require('gulp-eslint');
const plumber = require('gulp-plumber');
const fs = require('fs');
const path = require('path');

// paths of the packages for the compile-task
let pkgs = [
    { path: 'app/installer/', data: '../../composer.json' },
    { path: 'app/system/modules/theme/', data: '../../../../composer.json' }
];

// banner for the css files
const banner = '/*! <%= data.title %> <%= data.version %> | (c) 2014-2020 Pagekit | MIT License */\n';

const cldr = {
    cldr: path.join(__dirname, 'node_modules/cldr-core/supplemental/'),
    intl: path.join(__dirname, 'app/system/modules/intl/data/'),
    locales: path.join(__dirname, 'node_modules/cldr-localenames-modern/main/'),
    formats: path.join(__dirname, 'app/assets/vue-intl/dist/locales/'),
    languages: path.join(__dirname, 'app/system/languages/')
};

// general error handler for plumber
const errhandler = function (error) {
    this.emit('end');
    return console.error(error.toString());
};

/**
 * Copy specified "assets" from "node_modules" to the specified dirs.
 */
gulp.task('assets', () => {
    const dirs = [
        {
            path: 'app/assets/',
            assets: {
                uikit: '*',
                vue: '*',
                flatpickr: '*',
                lodash: { files: 'lodash*.js', folder: 'dist' }
            }
        },
        {
            path: 'app/system/modules/editor/app/assets/',
            assets: { tinymce: '*', marked: '*', codemirror: '*' }
        }
    ];

    return merge.apply(null, [].concat.apply([], dirs.map((dir) => (
        Object.keys(dir.assets).map((asset) => {
            const options = dir.assets[asset];
            const base = `${asset}/`;
            const src = path.join(__dirname, 'node_modules/') + base;
            let dest = path.join(__dirname, dir.path) + base;
            let files = `${src}**`;

            // Check options
            if ((typeof options === 'string') && (options !== '*')) {
                files = src + options;
            }

            // If you have options such as "files" and "folder" - the specified "files" will be copied to the specified "folder"
            if (typeof options === 'object') {
                if (options.files) files = src + options.files;
                if (options.folder) dest = dest + options.folder;
            }

            return gulp.src([files]).pipe(gulp.dest(dest));
        })
    ))));
});

/**
 * Compile all less files
 */
gulp.task('compile', () => {
    pkgs = pkgs.filter((pkg) => fs.existsSync(pkg.path));

    return merge.apply(null, pkgs.map((pkg) => (
        gulp.src(`${pkg.path}**/less/*.less`, { base: pkg.path })
            .pipe(plumber(errhandler))
            .pipe(less({ compress: true, relativeUrls: true }))
            .pipe(header(banner, { data: require(`./${pkg.path}${pkg.data}`) })) // eslint-disable-line import/no-dynamic-require, global-require
            .pipe(rename((file) => {
                // the compiled less file should be stored in the css/ folder instead of the less/ folder
                file.dirname = file.dirname.replace('less', 'css');
            }))
            .pipe(gulp.dest(pkg.path))
    )));
});

/**
 * Watch for changes in files
 */
gulp.task('watch', (cb) => {
    gulp.watch([
        './app/installer/**/*.less',
        './app/system/**/*.less'
    ], gulp.series('compile'));
});

/**
 * Lint all script files
 */
gulp.task('lint', () => gulp.src([
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
]).pipe(eslint()).pipe(eslint.format()).pipe(eslint.failOnError()));

gulp.task('cldr', (done) => {
    // territoryContainment
    const data = {};
    const json = JSON.parse(fs.readFileSync(`${cldr.cldr}territoryContainment.json`, 'utf8')).supplemental.territoryContainment;
    Object.keys(json).forEach((key) => {
        if (Number.isNaN(key)) return;
        data[key] = json[key]._contains;
    });

    fs.writeFileSync(`${cldr.intl}territoryContainment.json`, JSON.stringify(data));

    fs.readdirSync(cldr.languages)
        .filter((file) => fs.statSync(path.join(cldr.languages, file)).isDirectory())
        .forEach((src) => {
            const id = src.replace('_', '-');
            const shortId = id.substr(0, id.indexOf('-'));
            let found;

            ['languages', 'territories'].forEach((name) => {
                found = false;
                [id, shortId, 'en'].forEach((locale) => {
                    const file = `${cldr.locales}${locale}/${name}.json`;
                    if (!found && fs.existsSync(file)) {
                        found = true;
                        fs.writeFileSync(`${cldr.languages}${src}/${name}.json`, JSON.stringify(JSON.parse(fs.readFileSync(file, 'utf8')).main[locale].localeDisplayNames[name]));
                    }
                });
            });

            found = false;
            [id.toLowerCase(), shortId, 'en'].forEach((locale) => {
                const file = `${cldr.formats}${locale}.json`;
                if (!found && fs.existsSync(file)) {
                    found = true;
                    fs.writeFileSync(`{cldr.languages}${src}/formats.json`, fs.readFileSync(file, 'utf8'));
                }
            });
        });

    done();
});

gulp.task('default', gulp.series('assets', 'compile'));
