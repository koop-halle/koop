module.exports = function (grunt) {

    grunt.initConfig(
        {
            pkg      : grunt.file.readJSON('package.json'),
            modernizr: {
                dist: {
                    "devFile": "node_modules/grunt-modernizr/lib/modernizr-dev.js"
                }
            },
            babel    : {
                options: {
                    sourceMap: true
                },
                dist   : {
                    files: [
                        {
                            src: "js/application.js",
                            dest: "dist/application.js"
                        }
                    ]
                }
            },
            concat   : {
                options: {
                    separator: "\n\n\n\n",
                    process  : function (src, filepath) {
                        return '\n\n/* file: ' + filepath + ' */' + '\n\n' + src;
                    }
                },

                javascript: {
                    src   : [
                        'build/modernizr-custom.js',
                        'bower_components/jquery/dist/jquery.js',
                        'bower_components/bootstrap/dist/js/bootstrap.js',

                        'dist/application.js',
                        'bower_components/js-cookie/src/js.cookie.js',
                        'bower_components/chosen/chosen.jquery.js',
                        'bower_components/mustache.js/mustache.js',
                        'bower_components/moment/moment.js',
                        'bower_components/highcharts/highcharts.js',
                        'bower_components/highcharts/highcharts-more.js',
                        'bower_components/highcharts/modules/exporting.js',
                        'bower_components/chelsea/chelsea.js',
                        'bower_components/select2/select2.js',
                        'bower_components/select2/select2_locale_de.js',
                        'bower_components/dropzone/dist/min/dropzone.min.js',
                        'bower_components/filament-tablesaw/dist/tablesaw-init.js',
                        'bower_components/filament-tablesaw/dist/tablesaw.js',
                        'bower_components/RWD-Table-Patterns/dist/js/rwd-table.js',
                        'bower_components/slimScroll/jquery.slimscroll.js',
                        'bower_components/Sortable/Sortable.min.js',
                        'bower_components/jquery-colorbox/jquery.colorbox.js',
                        'bower_components/bxslider-4/dist/jquery.bxslider.js',
                        'bower_components/bootstrap-tabdrop/build/js/bootstrap-tabdrop.min.js',

                        'dist/application.js'
                    ],
                    nonull: true,
                    dest  : 'dist/app.js'
                },
                css       : {
                    src   : [

                        'bower_components/font-awesome/css/font-awesome.css',
                        'bower_components/bootstrap/dist/css/bootstrap.min.css',
                        'theme-default/css/font.css',
                        'theme-default/css/static/animate.css',
                        'theme-default/css/custom.colorbox.css',
                        'bower_components/bxslider-4/dist/jquery.bxslider.min.css',
                        'bower_components/chosen/chosen.css',
                        'bower_components/dropzone/dist/dropzone.css',
                        'bower_components/font-awesome/css/font-awesome.min.css',
                        'bower_components/select2/select2.css',
                        'bower_components/select2/select2-bootstrap.css',
                        'bower_components/filament-tablesaw/dist/tablesaw.css',
                        'bower_components/jquery-ui/themes/start/jquery-ui.min.css',
                        'bower_components/jquery-ui/themes/start/theme.css'
                    ],
                    nonull: true,
                    dest  : 'dist/app.css'
                }
            },
            copy     : {
                main: {
                    files: [
                        // fonts
                        {
                            expand : true,
                            src    : [
                                'bower_components/font-awesome/fonts/*',
                                'bower_components/bootstrap/fonts/*',
                                'theme-default/fonts/*'
                            ],
                            dest   : 'fonts/',
                            filter : 'isFile',
                            flatten: true
                        }
                    ]
                }
            },
            uglify   : {
                options: {
                    banner: '/*! built@<%= grunt.template.today("yyyy-mm-dd hh:MM:ss") %> */\n'
                },

                javascript: {
                    src : 'dist/app.js',
                    dest: 'dist/app.min.js'
                }
            },
            cssmin   : {
                options: {
                    mergeIntoShorthands: false,
                    sourceMap          : false,
                    roundingPrecision  : -1
                },
                target : {
                    files: {
                        'dist/app.min.css': ['dist/app.css']
                    }
                }
            }
        });

    grunt.loadNpmTasks("grunt-modernizr");
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-babel');
    grunt.registerTask(
        'default',
        [
            'copy',
            'babel',
            'concat',
            'uglify',
            'cssmin'
        ]
    );
};