const { src, dest, series } = require('gulp'),
      sass                  = require('gulp-sass'),
      autoprefixer          = require('gulp-autoprefixer');

function clean(cb) {
    cb();
}

function build(cb) {
    console.error(cb());

}

exports.scss = function () {
    return src(['./node_modules/bootstrap/scss/**/*.scss'])
        .pipe(sass.sync({
            outputStyle: 'expanded'
        }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(dest('./public/vendor/bootstrap'));
};


exports.build = build;
exports.default = series(clean, build);