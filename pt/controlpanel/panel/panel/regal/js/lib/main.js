// load dependecies
requirejs.config({
    baseUrl: 'js/lib',
    paths: {
        jquery: "jquery-2.1.4.min",
        underscore: 'underscore-min',
        wow: ['wow.min']
    },
    shim: {
        'bootstrap.min': ['jquery'],
        'jquery.easing.1.3': ['jquery'],
        'jquery.fittext': ['jquery'],
        'jquery-scrollspy': ['jquery'],
        'creative': ['jquery-scrollspy'],
        'jquery.iconpicker': ['jquery'],
        'jquery.timepicker': ['jquery'],
        'jquery.qtip': ['jquery'],
        'jquery.geocomplete.min': ['jquery'],
        'jquery-ui.min': ['jquery'],
        'jquery.prettyPhoto': ['jquery'],
        'RegalCalendar': ['jquery'],
        'jquery.ui.touch-punch.min': ['jquery'],
        'bootstrap-colorpicker.min': ['jquery'],
    }
});

var libs = ['jquery', 'bootstrap.min', 'wow', 'jquery.easing.1.3', 'jquery.fittext', 'jquery-scrollspy', 'creative', 'jquery.iconpicker', 'jquery.timepicker', 'jquery.qtip', 'jquery.geocomplete.min', 'jquery-ui.min', 'underscore', 'jquery.prettyPhoto', 'RegalCalendar', 'jquery.ui.touch-punch.min', 'bootstrap-colorpicker.min']

requirejs(libs, function ($) {
    $('.colorpicker').colorpicker({
        format: 'hex',
        color: '#F05F40'
    });
    createCalendar('white', '#F05F40');

    $('.change').on('click', function () {
        var base = $('#base').val();
        var color = $('#color').val();

        // remove calendar's map container and panels
        $('.map-container, .pac-container, .dayPanel, .eGroup, .event, #calendar *').remove();
        $('#calendar').removeClass('white dark hasDatepicker calendarPanel');
        $('hr.primary').css('border-color', color);
        $('.bg-primary').css('background-color', color);
        createCalendar(base, color);
    });

    // create calendar with color options
    function createCalendar(base, color) {
        $('#calendar').RegalCalendar({
            base: base,
            color: color
        });
    };
});