// Include gulp
var gulp = require("gulp");

// Include Our Plugins
var sass = require("gulp-sass");
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var rename = require("gulp-rename");
var autoprefixer = require("gulp-autoprefixer");
var minifycss = require("gulp-minify-css");
var soften = require("gulp-soften");
var size = require("gulp-size");

var path = require("path");
var rootAssets = path.resolve(__dirname, "./web/assets");

// Compile Our Sass
gulp.task("sass", function() {
    return gulp
        .src(rootAssets + "/css/sass/*.scss")
        .pipe(soften(4))
        .pipe(sass({ errLogToConsole: true }))
        .pipe(
        autoprefixer(
            "last 2 version",
            "safari 5",
            "ie 8",
            "ie 9",
            "opera 12.1",
            "ios 6",
            "android 4"
        )
    )
    .pipe(size({ title: "css" }))
    .pipe(gulp.dest(rootAssets + "/css"))
    .pipe(rename({ suffix: ".min" }))
    .pipe(minifycss())
    .pipe(size({ title: "css.min" }))
    .pipe(gulp.dest(rootAssets + "/css"))
});

// Concatenate & Minify JS
gulp.task("scripts", function() {
    return gulp
        .src([
            rootAssets + "/js/main.js"
        ])
        .pipe(soften(4))
        .pipe(size({ title: "js" }))
        .pipe(gulp.dest(rootAssets + "/js"))
        .pipe(rename("main.min.js"))
        .pipe(uglify())
        .pipe(size({ title: "js.min" }))
        .pipe(gulp.dest(rootAssets + "/js"))
});

gulp.task("fonts", function() {
    return gulp.src(rootAssets + "/fonts/**").pipe(gulp.dest(rootAssets + "/fonts/"));
});

// Watch Files For Changes
gulp.task("watch", function() {
    gulp.watch(rootAssets + "/js/main.js", ["scripts"]);
    gulp.watch(rootAssets + "/css/sass/**/*.scss", ["sass"]);
    gulp.watch(rootAssets + "/fonts/**", ["fonts"]);
});

// Default Task
gulp.task("default", [
    "sass",
    "scripts",
    "fonts",
    "watch"
]);
