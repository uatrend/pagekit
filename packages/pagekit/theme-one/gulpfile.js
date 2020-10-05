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
const path = require('path');
const composer = require('./composer.json');

// banner for the css files
const banner = '/*! <%= data.title %> <%= data.version %> | <%= data.copyright %> | <%= data.license %> License */\n';

/**
 * Copy specified "assets" from "node_modules" to the specified dirs.
 */
gulp.task('assets', () => {
    const dirs = [
        {
            path: 'app/assets/',
            assets: { uikit: '*' }
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
                if (options.folder) dest = !options.directly ? dest : path.join(__dirname, dir.path) + options.folder;
            }

            return gulp.src([files]).pipe(gulp.dest(dest));
        })
    ))));
});

/**
 * Compile all less files
 */
gulp.task('compile', () => gulp.src('less/theme.less', { base: __dirname })
    .pipe(less({ compress: true }))
    .pipe(header(banner, { data: composer }))
    .pipe(rename((file) => {
        // the compiled less file should be stored in the css/ folder instead of the less/ folder
        file.dirname = file.dirname.replace('less', 'css');
    }))
    .pipe(gulp.dest(__dirname)));

/**
 * Watch for changes in files
 */
gulp.task('watch', (cb) => {
    gulp.watch('less/**/*.less', gulp.series('compile'));
});

gulp.task('default', gulp.series('assets', 'compile'));
