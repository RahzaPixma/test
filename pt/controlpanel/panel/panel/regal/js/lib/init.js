/* dependencies */
requirejs.config({
    baseUrl: 'js/lib',
    paths: {
        jquery: 'jquery-2.1.4.min',
        underscore: 'underscore-min'
    },
    shim: {
        'bootstrap.min': ['jquery'],
        'jquery.easing.1.3': ['jquery'],
        'jquery.iconpicker': ['jquery'],
        'jquery.timepicker': ['jquery'],
        'jquery.qtip': ['jquery'],
        'jquery.geocomplete.min': ['jquery'],
        'jquery-ui.min': ['jquery'],
        'RegalCalendar': ['jquery'],
        'jquery.ui.touch-punch.min': ['jquery']
    }
});

var libs = ['jquery', 'bootstrap.min', 'underscore', 'jquery.easing.1.3', 'jquery.iconpicker', 'jquery.timepicker', 'jquery.qtip', 'jquery.geocomplete.min', 'jquery-ui.min', 'RegalCalendar', 'jquery.ui.touch-punch.min'];