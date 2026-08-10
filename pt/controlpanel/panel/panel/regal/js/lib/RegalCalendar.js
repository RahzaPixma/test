/**
 * RegalCalendarSQL.js  v1.0
 *
 * Copyright (C) 2015, Octavio Mejía.
 * All rights reserved.
 *
 **/

(function ($) {
    var methods = {
        init: function (options) {
            var settings = $.extend({
                base: 'white', // base colour
                compact: false, // reduce the size of the calendar to minimun
                color: '#9EC02B', // colour scheme
                enter: 'fadeInUp', // enter animation of tooltips
                exit: 'fadeOutDown', // exit animation of tooltips
                animation: '', // easing animation
                show: 'click', // name of the event to show tooltips
                startWeek: 0, // day number that calendar starts, 0 is for sunday, 1 is for monday...
                tooltipPosition: 'top', // tooltip position in relation with day number
                tooltip: 'bootstrap', // tooltip theme
                defaultDate: null, // default selected date
                mnDate: null, // min date range
                mxDate: null, // max date range
                timeFormat: 'ampm', // time format (ampm/hrs)
                inputDate: false, // input element that will receive date
                inputEvent: false, // input element that will receive event
                inputLocation: false, // input element that will receive location
                twitter: 'IIM', // twitter username
                twitterText: 'Attend to ', // tweet initial text
                itemsPerPage: 2, // items per page when eventsPosition is set to inline
                tooltipDelay: 60000, // tooltip show duration
                dayPanel: 'inline', // position of the day panel (floating, inline, false)
                eventsPosition: 'tooltip', // events detail position (tooltip/inline/lightbox)
//                apiUrl: 'http://api.openweathermap.org/data/2.5/forecast/daily', // weather service url
				apiUrl: '',
                apiKey: '60d4d91adae9c5d0ae8b09c1b07dab17', // weather service key
                weatherLanguaje: 'en', // weather service languaje,
                monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                dateFormat: 'dd/mm/yy', // output date format dd/mm/yy = 26/11/1983, yy/mm/dd = 1983/11/26
                dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], // full day names
                dayNamesMin: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"], // reduced day names
                url: 'php/Twitter.php', // url to search tweets
//				url: '',
                sourceURL: 'php/GetCalendar.php?mode=1', // url to retrieve events
                editableURL: 'php/UpdateCalendar.php', // url to update events
                editable: false, // disable/enable edit mode
                subscribe: true // disable/enable subscriptions

            }, options);

            var $this = $(this);
            var loading = false;
            var $calendar = $this.find('table.ui-datepicker-calendar');

            // create a container to wrap calendar if setting.compact is set
            if (settings.compact) {
                $this.wrap('<div class="compact"></div>');
            }

            if (settings.subscribe) {
                $('body').append('<div class="modal fade bookmark-modal" id="eventSubscribeModal" tabindex="-1" role="dialog"> \
			<div class="modal-dialog" role="document"> \
				<div class="modal-content"> \
					<div class="modal-header"> \
						<h4 class="modal-title" id="myModalLabel">Bookmark <span class="moda-title-event"></span></h4> \
					</div> \
					<div class="modal-body"> \
						<div> \
							<div class="form-group has-feedback"> \
								<label for="Email">Email</label> \
								<input type="email" name="email" class="form-control" id="rEmail" /> \
							</div> \
						</div> \
						<div> \
							<div class="form-group has-feedback"> \
								<label for="Name">Name</label> \
								<input  type="text" name="name" class="form-control" id="rName" /> \
							</div> \
						</div> \
						<div class="alert alert-danger alert-dismissible fade in" role="alert"> \
						  <button type="button" class="close close-alert" aria-label="Close"><span aria-hidden="true">×</span></button> \
						  <strong>Error.</strong> Please provide a valid email. \
						</div> \
					</div> \
					<div class="modal-footer"> \
						<button type="button" class="btn btn-primary" data-dismiss="modal"> \
							<i class="fa fa-remove"></i>Close</button> \
						<button type="button" class="btn btn-danger" id="rbookmark"> \
							<i class="fa fa-check"></i>Yes</button> \
					</div> \
				</div> \
			</div> \
		</div>');

                $('body').on('click', '.close-alert', function () {
                    $(this).parent().fadeOut();
                });
                $('#eventSubscribeModal').modal({
                    show: false
                });
				
//disable subscribe				
/**ASAL				
                $('body').on('click', 'i.subscribe', function () {
                    $('.moda-title-event').text($(this).attr('data-title')).data('event', $(this).attr('data-event'));
                    $('#eventSubscribeModal').modal('show');
                });
ASAL**/				
            }

            // set base color and track calendar navigation direction
            $this.addClass(settings.base);
            $this.css({
                position: 'relative'
            });
            $this.on('mouseenter', 'a.ui-datepicker-next', function () {
                $this.data('dir', 'Next');
            });
            $this.on('mouseenter', 'a.ui-datepicker-prev', function () {
                $this.data('dir', 'Prev');
            });


//map-location
/**ASAL			
            // set the map configuration and binds the event to show it
            //$('body').off('click', 'section.map, .location');
			$('body').off('click', 'section.map, i.location');
		
            $('body').on('click', 'section.map, i.location', function () {
                $('body').append('<section class="map-container"><img class="map-loader" src="img/loaderw.gif" /><section class="map-wrapper"></section><i class="fa fa-times-circle"></i></section>');
 
                var $this = $(this);
                var location = $this.attr('data-location');
                var $map = $('.map-container');
                $map.show();

                $map.geocomplete({
                    map: $map.children('section'),
                    location: location,
                    color: settings.color,
                    mapOptions: {
                        scrollwheel: true,
                        mapTypeControl: false,
                        backgroundColor: '#000',
						styles:[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f6ebcb"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"color":"#f7f1df"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"on"},{"color":"#f3dd9d"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"},{"visibility":"on"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color": settings.color }]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#e6dcbd"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#226eff"}]}]
                    }
                });
            }).on('click', '#rbookmark', function () {

                var subscrption = {
                    email: $('#rEmail').val(),
                    name: $('#rName').val(),
                    event: $('.moda-title-event').data('event')
                }

                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                var resultEmail = re.test(subscrption.email);

                if (resultEmail) {
                    $.post(settings.editableURL, {
                        email: subscrption.email,
                        name: subscrption.name,
                        event_id: subscrption.event,
                        action: 5
                    }, function (response) {
                        $('#eventSubscribeModal').modal('hide');
                        $('#rEmail, #rName').val('');
                    });
                } else
                    $('#eventSubscribeModal .alert-danger').fadeIn();

					
            });
ASAL**/

            // open twitter window
            $('body').off('click', 'a.tweet');
            $('body').on('click', 'a.tweet', function (e) {
                e.preventDefault();
                var loc = window.location.href;
                var title = $(this).attr('data-title');

//ASAL                window.open('https://twitter.com/intent/tweet?original_referer=' + window.location.href.replace('#', '') + '&related=' + settings.twitter + 'screen_name=' + settings.twitter + '&share_with_retweet=never&text=' + settings.twitter + ' - ' + settings.twitterText + title + '&tw_p=tweetbutton', 'twitterwindow', 'height=450, width=550, top=' + ($(window).height() / 2 - 225) + ', left=' + $(window).width() / 2 + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
           });

            // displays event weather information
            $('body').on('click', '.events-item, .multiple', function (e) {
                console.log(e);
                if (e.toElement.className != 'fa fa-twitter' && e.toElement.className != 'location fa fa-map-marker' && e.toElement.className != 'delete fa fa-trash') {
                    var query = $(this).attr('data-cityweather');
                    getWeather(query, $(this).attr('data-title'), settings);
                }
            });

            // remove the map
            $('body').on('click', 'section.map-container i', function () {
                $('.map-container').hide().remove();
            });

            // configure calendar
            calendar = $this.datepicker({
                defaultDate: settings.defaultDate,
                minDate: settings.mnDate,
                maxDate: settings.mxDate,
                dateFormat: settings.dateFormat,
                monthNames: settings.monthNames,
                dayNames: settings.dayNames,
                dayNamesMin: settings.dayNamesMin,
                firstDay: settings.startWeek,
                hideIfNoPrevNext: true,
                showOtherMonths: true,
                onSelect: function (date, inst) {
                    // set up events tooltips and inline containers
                    var $day = $this.find('td a:containsNumber(' + inst.selectedDay + ')');
                    var $events = $day.siblings('.event');
                    if ($events.length == 0)
                        $events = $day.siblings('.multiple-container').find('.event');
                    if (!$day.hasClass('ui-state-active') || $day.hasClass('ui-state-highlight'))
                        getWeather($events.eq(0).attr('data-cityWeather'), $events.eq(0).attr('data-title'), settings);
                    if (settings.eventsPosition == "inline")
                        createEventsContainer($this, $events, settings);
                    inst.inline = false;
                    $(this).find(".ui-datepicker-calendar .ui-datepicker-current-day").removeClass("ui-datepicker-current-day").children().removeClass("ui-state-active");
                    $(this).find(".ui-datepicker-calendar tbody a").each(function () {
                        if ($(this).text() == inst.selectedDay) {
                            $(this).addClass("ui-state-active");
                            $(this).parent().addClass("ui-datepicker-current-day");
                        }
                    });
                    // ouput event information
                    if (settings.inputDate)
                        $(settings.inputDate).val(date);
                    if (settings.inputEvent) {
                        var $event = $this.find('td a:containsNumber(' + inst.selectedDay + ')');
                        $(settings.inputEvent).val($event.data('title'));
                    }
                    if (settings.inputLocation) {
                        var $event = $this.find('td a:containsNumber(' + inst.selectedDay + ')');
                        $(settings.inputLocation).val($event.data('location'));
                    }
                    // update day panel information
                    updateDayPanel($this, settings);
                    if (settings.editable && settings.eventsPosition != "lightBox") {
                        handleAddButton($day, $this);
                        var $active = $this.find('.ui-state-active');
                        var currentDate = $active.text() + '/' + (parseInt($active.parent('td').attr('data-month')) + 1) + '/' + $active.parent('td').attr('data-year');
                        $('.input-date').val(currentDate);
                    }

                },
                onChangeMonthYear: function (y, m, i) {
                    // reset events on change month year
                    $('.qtip').qtip('destroy', true);
                    $.getJSON(settings.sourceURL, {
                        month: m,
                        year: y
                    }).done(function (events) {
                        setEvents($this, settings, events);
                        if (settings.animation) {
                            var direction = settings.animation.indexOf('rc') >= 0 ? $this.data('dir') : '';
                            if (window.navigator.appName != 'Microsoft Internet Explorer') {
                                $this.find('table.ui-datepicker-calendar, div.ui-datepicker-title').addClass(settings.animation + direction + ' animate');
                            } else {
                                $this.find('table.ui-datepicker-calendar').css({
                                        'opacity': 1
                                    })
                                    .animate({
                                        'opacity': 1
                                    }, {
                                        'duration': 2300,
                                        'easing': 'linear'
                                    });
                            }
                        }
                        setControls($this, settings);
                        setColors($this, settings);

                    });
                }
            });

            // get current month and year values
            var m = $this.datepicker("getDate").getMonth() + 1;
            var y = $this.datepicker("getDate").getFullYear();

            // create year navigation controls
            $this.prepend('<span class="regal-prevyear_gray fa fa-angle-left"></span><span class="regal-nextyear_gray fa fa-angle-right"></span>');

            // get events from the datasource
            $.getJSON(settings.sourceURL, {
                month: m,
                year: y
            }).done(function (events) {
                setEvents($this, settings, events);
            });

            // set up the controls and color scheme
            setControls($this, settings);
            setColors($this, settings);

            // bind years navigation
            $this.find('.regal-prevyear_gray').on('click', function () {
                $.datepicker._adjustDate($this, -1, 'Y');
            });
            $this.find('.regal-nextyear_gray').on('click', function () {
                $.datepicker._adjustDate($this, +1, 'Y');
            });
            var color = settings.color;

            // set up tweets box
            if (settings.twitter) {
                $this.append('<span class="twitter_search">' + settings.twitter + '</span>');
                var $twitter = $this.find('.twitter_search').twitterpopup({
                    colorExterior: color,
                    modal: settings.modal,
                    container: $this,
                    url: settings.url
                });
                if (settings.modal)
                    $twitter.css('color', '#FFF');

                $this.find('span.twitter_search').css('background-color', settings.color);

            }

            // resize the inline event containers
            $(window).resize(function () {
                var cWidth = $('.events-item').width() - $('.events-item .time').width() - parseInt($('.events-item .time').css('margin-left')) - 6;
                $('.item-content').width(cWidth);
            });

            $('.twitter_search').before('<section class="events-container"></section>');

            // create and set day panel
            if (settings.dayPanel) {
                createDayPanel($this, settings);
                updateDayPanel($this, settings);

                if (settings.dayPanel == 'floating') {
                    $('.dayPanel.floating').scrollToFixed({
                        limit: $('.events-container').offset().top + 100,
                        zIndex: 10000,
                        preAbsolute: function () {
                            $(this).addClass('unfixed');
                        },
                        preFixed: function () {
                            $(this).removeClass('unfixed');
                        }
                    });
                }
            }

            // create a form to add new events
            if (settings.editable) {
                var scrollTheme = settings.base == 'dark' ? 'light' : 'dark';
                var inputs =
                    '<div class="event-input">' +
                    '<form>' +
                    '<input type="text" class="input-title" name="title" placeholder="header">' +
                    '<input type="text" class="input-title" name="title" placeholder="tajuk">' +
                    '<input type="text" class="input-title" name="title" placeholder="penceramah">' +
                    '<input type="text" class="input-title" name="title" placeholder="waktu">' +
                    '<input type="text" class="input-title" name="title" placeholder="masa">' +
                    '<input type="text" class="input-text" name="text" placeholder="text">' +
                    '<input type="text" class="input-location"  name="location" placeholder="location">' +
                    '<input type="text" class="input-weather" name="cityWeather" placeholder="city wather">' +
                    '<input type="text" class="input-preview" name="previewText" placeholder="preview">' +
                    '<input type="text" class="input-icon" name="icon" placeholder="icon">' +
                    '<input type="hidden" name="date" class="input-date">' +
                    '<div class="event-time">' +
                    '<input type="text" class="input-hour" name="hour" placeholder="hour">' +
                    '</div>' +
                    '</form>' +
                    '<div class="addActions"><div class="cancel"><i class="fa fa-close"></i></div><div class="confirm"><i class="fa fa-check"></i></div></div>' +
                    '<div class="feedback"><p>Can´t pass the process of validation.</p><div>' +
                    '</div>';
                $this.append('<section class="' + settings.base + ' addForm"><img class="add-loader" src="img/loaderw.gif">' + inputs + '</section>');
                $('.input-location').on('change blur', function () {
                    var $this = $(this);
                    setTimeout(function () {
                        var location = $this.val();
                        $('.input-weather').val(location);
                    }, 270);
                });
                $('.input-location').geocomplete();
                $('.input-icon').iconpicker({
                    placement: 'top'
                });
                $('.addForm input').css({
                    'border-bottom-color': settings.color
                });
                $('.addForm .cancel,  .addForm .confirm').css({
                    'background-color': settings.color
                });
                $('.iconpicker-items').mCustomScrollbar({
                    theme: scrollTheme,
                    scrollInertia: 0
                });
                $('.input-hour').timepicker({
                    timeFormat: 'H:i'
                });

                $('.input-hour').on('showTimepicker', function () {
                    $('.ui-timepicker-wrapper').addClass(settings.base).mCustomScrollbar({
                        theme: scrollTheme,
                        scrollInertia: 0
                    });
                });

                setTimeout(function () {
                    $('.pac-container').addClass(settings.base);
                }, 300);
                initEditable($this, settings);
            }
        },
        // get selected day events
        getEvents: function () {
            var events = apiEvents($(this), 'day');
            return events;
        },
        // get events of the current month
        getMonthEvents: function () {
            var events = apiEvents($(this), 'month');
            return events;
        },
        // get selected date
        getDate: function () {
            var dateFormat = $(this).datepicker('option', 'dateFormat');
            var currentDate = $(this).datepicker({
                dateFormat: dateFormat
            }).val();
            return currentDate;
        }
    };

    // init plugin
    $.fn.RegalCalendar = function (options) {
        if (methods[options]) {
            return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof options === 'object' || !options) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + options + ' does not exist.');
        }
    }
})(jQuery);

// populate event information
function apiEvents($calendar, type) {
    var $events = type == 'month' ? $calendar.find('div.event') : $calendar.find('.ui-state-active').parent('td').find('div.event');
    var events = new Array();
    $events.each(function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var date = $this.attr('data-date');
        var time = $this.attr('data-time');
        var title = $this.attr('data-title');
        var txt = $this.attr('data-txt');
        var location = $this.attr('data-location');
        var cityweather = $this.attr('data-cityweather');
        var icon = $this.attr('data-icon');

        var event = {
            id: id,
            date: date,
            time: time,
            title: title,
            txt: txt,
            location: location,
            cityweather: cityweather,
            icon: icon
        }
        events.push(event);
    })
    return events;
}

// create add button next ot event day
function handleAddButton($day, $calendar) {
    $('i.addButton').remove();
    $day.after('<i class="addButton fa fa-plus-circle" title="add event"></i>');
}

// check the event data before to send it
function checkData($form) {
    var result = true;
    var $inputs = $form.find('input').not('.iconpicker-search');
    $inputs.each(function () {
        var $input = $(this);
        if ($input.val().length == 0) {
            $input.addClass('error');
            result = false;
        }
    });

    return result;
}

// get weathewr icon, searching by weather code
function getWeatherIcon(id) {
    var icon;
    var code = parseInt(id);

    if (code >= 200 && code < 232)
        icon = "wi wi-thunderstorm";
    else if (code >= 300 && code < 321)
        icon = "wi wi-sprinkle";
    else if (code >= 500 && code < 504)
        icon = "wi wi-rain";
    else if (code == 511)
        icon = "wi wi-hail";
    else if (code >= 520 && code < 531)
        icon = "wi wi-showers";
    else if (code >= 600 && code < 622)
        icon = "wi wi-snow";
    else if (code >= 701 && code < 781)
        icon = "wi wi-fog";
    else if (code == 800)
        icon = "wi wi-day-sunny";
    else if (code == 801)
        icon = "wi wi-day-cloudy";
    else if (code == 802)
        icon = "wi wi-cloud";
    else if (code == 803)
        icon = "wi wi-cloudy";
    else if (code == 804)
        icon = "wi wi-cloudy";
    else if (code == 900)
        icon = "wi wi-tornado";
    else if (code == 901)
        icon = "wi wi-strong-wind";
    else if (code == 902)
        icon = "wi wi-hurricane";
    else if (code == 903)
        icon = "wi wi-snowflake-cold";
    else if (code == 904)
        icon = "wi wi-hot";
    else if (code == 905)
        icon = "wi wi-windy";
    else if (code == 906)
        icon = "wi wi-hail";

    return icon;
}

// set up weather service configuration and get the current weather
function getWeather(query, title, settings) {
    var url = settings.apiUrl + '?q=' + query + '&cnt=1&units=metric&temp=Celsius&APPID=' + settings.apiKey + '&lang=' + settings.weatherLanguaje;
    //var url = settings.apiUrl + '?q=' + query + '&cnt=1&units=metric&temp=Celsius&lang=' + settings.weatherLanguaje;
    $('span.dayEventTitle').text('');

    if (typeof (query) != 'undefined') {
        var $loader = $('img.weather-loader');
        var $info = $('.weatherIcon i, p.weatherTemperature,  p.weatherTemperatureAlt, p.weatherDescription, p.weatherCity');
        $('.weatherInfo p, .weatherIcon i, .dayEventTitle').show();
        $loader.fadeIn();
        $info.hide();

        $.ajax({
            type: 'GET',
            url: url,
            contentType: "application/json",
            dataType: 'jsonp',
        }).done(function (json) {
            $('.weatherIcon i').removeClass().addClass(getWeatherIcon(json.list[0].weather[0].id));
            $('p.weatherTemperature').html(Math.round(json.list[0].temp.max) + '°C&nbsp;/&nbsp;' + Math.round(json.list[0].temp.min) + '°C');
            $('p.weatherTemperatureAlt').html(Math.round(json.list[0].temp.max * 9 / 5 + 32) + '°F&nbsp;/&nbsp;' + Math.round(json.list[0].temp.min * 9 / 5 + 32) + '°F');
            $('p.weatherDescription').text(json.list[0].weather[0].description);
            $('p.weatherCity').text(json.city.name.length > 0 ? json.city.name : json.city.country);
            $('span.dayEventTitle').text(title);

            $loader.fadeOut();
            $info.fadeIn();

        }).fail(function (error) {
            console.log(error);
        });
    } else {
        $('.weatherInfo p, .weatherIcon i').fadeOut();
    }
}

function updateDayPanel($this, settings) {
    var day = $this.find('a.ui-state-active').text();
    var index = $this.find('a.ui-state-active').parents('tr').children('td').index($this.find('a.ui-state-active').parent('td'));
    var dayName = settings.dayNames[index];

    $('.dayNumber').text(day);
    $('.dayText').text(dayName);
}

// create a container to soy weather infomration
function createDayPanel($this, settings) {
    //<span class="dayEventTitle"></span>
    $this.addClass('calendarPanel');
    $this.before('<section style="background-color: ' + settings.color + '" class="dayPanel ' + settings.base + ' ' + settings.dayPanel + '"><section class="dayInfo"><span class="dayNumber"></span><span class="dayText"></span></section><section class="weatherIcon"><i></i><span class="dayEventTitle"></span></section><section class="weatherInfo"><img class="weather-loader" src="img/loaderw.gif" /><p class="weatherCity"></p><p class="weatherDescription"></p><p class="weatherTemperature"></p><p class="weatherTemperatureAlt"></p></section></section>');
}

// configure pagination when inline option is on
function setPagination($this, settings) {
    $this.pajinate({
        items_per_page: settings.itemsPerPage,
        item_container_id: '.events-container',
        nav_panel_id: '.events-navigation',
        nav_label_prev: '<i class="fa fa-long-arrow-left"></i>',
        nav_label_next: '<i class="fa fa-long-arrow-right"></i>',
        show_first_last: false
    });
}

// create events container and conternt
function createEventsContainer($this, $events, settings) {
    var $container = $('.events-container');
    $container.find('.events-item').remove();
    $('.events-navigation').remove();
    if ($events.length > 0) {
        $container.before('<nav class="events-navigation"></nav');

        $events.each(function () {
            var id = $(this).attr('data-id');
            var title = $(this).attr('data-title');
            var location = $(this).attr('data-location');
            var date = $(this).attr('data-date').split('/');
            var timeFull = $(this).attr('data-time');
            var time = $(this).attr('data-time').split(':');
            var icon = $(this).attr('data-icon');
            var text = $(this).attr('data-txt');
            var cityWeather = $(this).attr('data-cityWeather');
            var oposite = '';
            var tFormat;
            var tHour;
            var until = new Date(date[2], date[1] - 1, date[0], time[0], parseInt(time[1]), 0, 0);
            if (settings.timeFormat == 'hrs') {
                tFormat = 'hrs';
                tHour = $(this).attr('data-time');
            } else {
                var hours = time[0];
                var minutes = time[1];
                var tFormat = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12;
                tHour = hours + ':' + minutes;
            }

            // add witter and editable buttons
            var txtbutton = settings.twitter ? '<a title="tweet" data-title="' + title + '" href="#" class="tweet" target="_blank"><i class="fa fa-twitter" style="color:' + settings.color + '"></i></a>' : '';
            var txtbuttonSusbscribe = settings.subscribe ? '<i class="fa fa-bookmark subscribe" data-event="' + id + '" data-title="' + title + '" style="color:' + settings.color + '"></i>' : '';
            var delButton = settings.editable ? '<i title="delete" data-multiID="' + id + '" data-id="' + id + '" class="delete fa fa-trash" style="color:' + settings.color + '"></i>' : '';

            $container.append('<div class="events-item" data-title="' + title + '" data-cityWeather="' + cityWeather + '"><div class="time"><span class="hour">' + tHour + '</span><span class="format">' + tFormat + '</span></div><article class="item-content"><section class="title">' + title + '</section><section class="body">' + _.unescape(text) + '</section></article><i  title="' + title + '" class="icon fa fa-' + icon + '" style="color:' + settings.color + '"></i><section class="inline-actions">' + txtbuttonSusbscribe + txtbutton + '<i title="' + location + '" data-location="' + location + '" class="location fa fa-map-marker" style="color:' + settings.color + '"></i>' + '' + '</section><section class="count"><div></div></section></div>');

            $('.count div').countdown({
                until: until,
                layout: '{dn} {dl} {hnn}{sep}{mnn}{sep}{snn}'
            });

        });

        // calculate item content width and set up it
        var cWidth = $('.events-item').width() - $('.events-item .time').width() - parseInt($('.events-item .time').css('margin-left')) - 6;
        var scrollTheme = settings.base == 'dark' ? 'light' : 'dark';

        $('.item-content').width(cWidth);
        $('.item-content section.body').mCustomScrollbar({
            theme: scrollTheme,
            scrollInertia: 0
        });
        $('.hour').css('background-color', settings.color);

        //setClasses($container);
        setPagination($this, settings);
    }

}


function initEditable($this, settings) {
    // show the form to add a new event
    $this.off('click', 'tbody td');
    $this.on('click', 'tbody td', function (event) {
        if ($(event.target).hasClass('addButton')) {
            $('.addForm').fadeIn();
        }
    });

    // close the form  and clear it
    $this.on('click', '.addActions .cancel', function () {
        clearForm();
    });

    // send event data
    $this.off('click', '.addActions .confirm');
    $this.on('click', '.addActions .confirm', function () {
        var $form = $('.addForm');
        var submit = checkData($form);
        if (submit) {
            var newEvent = $form.find('form').serialize() + "&action=1";
            onSubmit('loading');
            $.ajax({
                url: settings.editableURL,
                type: 'POST',
                data: newEvent
            }).done(function (e) {
                console.log(e);
                clearForm();
                $(".qtip").qtip('destroy', true);
                resetEvents($this, settings);
                onSubmit('end');
            }).fail(function (e) {
                console.log(e);
                $form.find('feedback').show().children('p').text(e);
                onSubmit('end');
            });
        }
    });

    // delete selected event
    $('body').on('click', '.tooltip-actions i.delete, .inline-actions i.delete, .pp_buttons .delete', function () {
        var id = $(this).attr('data-id');
        var type = $(this).hasClass('single') ? 'single' : 'multi';
        $('.weatherInfo p, .weatherIcon i, .dayEventTitle').hide();
        removeEvent(id, type, $(this), $this, settings);
    });

    // set up icon picker
    $this.on('click', '.iconpicker-item', function () {
        $('.iconpicker-item').css('background-color', 'transparent');
        $(this).css('background-color', settings.color);
    });

    // prevents user to input letters in time box
    $this.on('keydown', '.input-hour', function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $this.on('focus', '.addForm input', function () {
        $(this).removeClass('error');
    });

}

// create navigation controls and bind theri events
function setControls($this, settings) {
    var $nextMonth = $this.find('.ui-datepicker-next'),
        $prevMonth = $this.find('.ui-datepicker-prev'),
        $nextYear = $this.find('.regal-nextyear_gray'),
        $prevYear = $this.find('.regal-prevyear_gray'),
        $days = $this.find('.ui-datepicker td'),
        $events = $this.find('.event'),
        originalColor = $nextMonth.css('color'),
        originalDayColor = $this.find('.ui-datepicker td a').css('color'),
        originalEventColor = $days.css('color');

    $days.on('click', function (event) {
        if ($(event.target).hasClass('addButton')) {
            $('.addForm').fadeIn();
        }
        $this.find('a.ui-state-default').css({
            color: originalDayColor,
            'background-color': 'transparent',
            'border-color': 'transparent'
        });
        $(this).find('a').css({
            color: '#FFF',
            'background-color': settings.color
        });
    });

    $nextMonth.on('mouseenter', function () {
        $(this).css('color', settings.color);
    }).on('mouseleave', function () {
        $(this).css('color', originalColor);
    });

    $prevMonth.on('mouseenter', function () {
        $(this).css('color', settings.color);
    }).on('mouseleave', function () {
        $(this).css('color', originalColor);
    });

    $nextYear.on('mouseenter', function () {
        $(this).css('color', settings.color);
    }).on('mouseleave', function () {
        $(this).css('color', originalColor);
    });

    $prevYear.on('mouseenter', function () {
        $(this).css('color', settings.color);
    }).on('mouseleave', function () {
        $(this).css('color', originalColor);
    });

    $days.find('a').on('mouseenter', function () {
        if (!$(this).hasClass('ui-state-active'))
            $(this).css({
                color: settings.color,
                'border-color': settings.color
            });
    }).on('mouseleave', function () {
        if (!$(this).hasClass('ui-state-active'))
            $(this).css({
                color: originalDayColor,
                'border-color': 'transparent'
            });
    });

    $this.on('mouseenter', '.event, .addButton, .eGroup', function () {
        $(this).css('color', settings.color);
    }).on('mouseleave', '.event, .addButton, .eGroup', function () {
        $(this).css('color', originalDayColor);
    });
}

// show / hide loader and input boxes
function onSubmit(state) {
    var $inputs = $('.addForm').find('form input');

    if (state == 'loading') {
        $inputs.prop('disabled', true);
        $('.add-loader').fadeIn();
    } else {
        $('.add-loader').fadeOut();
        $inputs.prop('disabled', false);
    }
}

// clear events and create them again
function resetEvents($this, settings) {
    var $daysContent = $this.find('td *').not('a');
    var m = $this.datepicker("getDate").getMonth() + 1;
    var y = $this.datepicker("getDate").getFullYear();

    $.getJSON(settings.sourceURL, {
        month: m,
        year: y
    }).done(function (events) {
        $daysContent.remove();
        setEvents($this, settings, events);
    });
}

// reset add from
function clearForm() {
    var $form = $('.addForm');
    $form.find('input').val('').removeClass('error');
    $form.fadeOut();
}

// remove an event from calendar and from datasource
function removeEvent(id, type, $this, $calendar, settings) {
    var tooltipId = '#qtip-ev-' + id;
    var $parentTooltip;
    var events = 0;

    if ($this.hasClass('multi')) {
        var $parentTooltip = $('#qtip-' + $this.attr('data-multiID'));
        events = $parentTooltip.find('.event').length;
    }

    $('[data-id="' + id + '"]').next('span').remove().end().remove();

    $(tooltipId).qtip('destroy', true);

    if (events == 2) {
        var date = $parentTooltip.find('.event').attr('data-date').split('/');
        date[1] = (parseInt(date[1]) - 1).toString();
        var $event = $parentTooltip.find('.event');
        var $found = $calendar.find("td[data-month='" + date[1] + "'][data-year='" + date[2] + "'] a:containsNumber(" + date[0] + ")").parent('td');
        $found.find('.eGroup, .multiple-container').remove();
        $event.removeClass('multiple').appendTo($found);
        $event.children('span').addClass('rg-custom').appendTo($found);
        $parentTooltip.qtip('destroy', true);

        if (settings.eventsPosition == 'tooltip') {
            var idRemain = $event.attr('data-id');
            $('#qtip-ev-' + idRemain).qtip('destroy', true);
            setTimeout(function () {
                createTooltip($calendar, $event, settings.timeFormat, settings.tooltipPosition, settings.editable, settings.color, settings.base, settings.twitter, settings.show, settings.delay, settings.exit, settings.enter, settings.tooltip, 'single', '', false, settings.suscribe);
            }, 100);
        }
    }
    if (settings.eventsPosition == 'inline') {
        $this.parents('.events-item').remove();
    }
    $.ajax({
        url: settings.editableDeleteURL,
        type: 'POST',
        data: {
            event_id: id
        }
    }).done(function (e) {
        console.log(e);
    }).fail(function (e) {
        console.log(e);
    });

}

// set up color scheme
function setColors($this, settings) {
    $this.find('table.ui-datepicker-calendar tr th').css({
            color: settings.color,
            'border-top-color': settings.color
        }),
        $this.find('.ui-state-active').css({
            color: '#FFF',
            'background-color': settings.color
        });
    $('button#rbookmark').css({
        'background-color': settings.color,
        'border-color': settings.color
    });
}

// get the link that contains a specified number
$.extend($.expr[':'], {
    containsNumber: function (a, i, m) {
        var text = $(a).text();
        return parseInt(text) == parseInt(m[3]);
    }
});

// create the struture of each event, and multi-event contianer
function setEvents($this, settings, events) {
    console.log(events);
    $.each(events, function (i) {
        var id = events[i].id;
        var customText = events[i].previewText.length == 0 ? '' : '<span class="rg-custom">' + events[i].previewText + '</span>';
        var date = events[i].date.split('/');
        var time = events[i].time;
        var icon = events[i].icon.length == 0 ? '' : '<div class="event fa ' + events[i].icon + '" data-txt="' + _.escape(events[i].text) + '" data-id="' + id + '" data-icon="' + events[i].icon + '" data-date="' + events[i].date + '" data-time="' + events[i].time + '" data-title="' + events[i].title + '" data-location="' + events[i].location + '"  data-cityWeather="' + events[i].cityWeather + '" ></div>' + customText;
        date[1] = (parseInt(date[1]) - 1).toString();
        var $day = $this.find("a:containsNumber(" + date[0] + ")");
        var $found = $day.parent('td');
        $found.append(icon);

        var $event = $found.find('div.event');
        var multiple = $event.size();

        if (settings.eventsPosition == 'lightBox') {
            var txtbutton = settings.twitter ? '<a title="tweet" data-title="' + events[i].title + '" href="#" class="tweet" target="_blank"><i class="fa fa-twitter" style="color:' + settings.color + '"></i></a>' : '';
            var txtbuttonSusbscribe = settings.subscribe ? '<i class="fa fa-bookmark subscribe" data-event="' + id + '" data-title="' + events[i].title + '" style="color:' + settings.color + '"></i>' : '';

            var rel = "event[" + events[i].date + "]";
            var lightBoxItem = '<div id="ev-' + events[i].id + '"  class="hide"> \
													<article data-id="ev-' + events[i].id + '"> \
														<section class="pp_title"><p>' + events[i].title + '</p></section> \
														<section class="pp_text"> \
															' + _.unescape(events[i].text) + ' \
														</section> \
														<section class="pp_buttons"> \
															<i title="' + events[i].location + '" data-location="' + events[i].location + '" class="location fa fa-map-marker" style="color:' + settings.color + '"></i> \
															' + txtbutton + ' \
															' + txtbuttonSusbscribe + ' \
														</section> \
													</article> \
												</div>';
            $day.attr({
                'href': '#ev-' + events[i].id,
                'rel': rel
            });

            $found.append(lightBoxItem);
            $found.append('<a class="hide" href="#ev-' + events[i].id + '" rel="' + rel + '"></a>');
        }

        if (multiple > 1) {
            $found.find('.rg-custom').remove();
            $('span.event[data-date="' + events[i].date + '"]').addClass('multiple');
            $event.each(function () {
                $(this).addClass('multiple').html('<span>' + $(this).attr('data-title') + '</span>');
            });
        }
    });

    if (settings.eventsPosition == "lightBox") {
        $('td').has('a.hide').each(function () {
            $(this).children('a.hide').last().remove();
        }).promise().done(function () {
            $("a[rel^='event']").prettyPhoto({
                calendar: settings
            });
        });
    }

    var $multiple = $('div.regalcalendar td').has('div.multiple');
    $multiple.each(function () {
        $(this).append('<div class="eGroup fa fa-calendar"></div><div class="multiple-container"></div>');
        var $container = $(this).find('div.multiple-container');
        var $mEvents = $(this).find('.multiple');
        $mEvents.appendTo($container);
    }).promise().done(function () {
        if (settings.dayPanel) {
            var $day = $this.find('td a.ui-state-active');
            var $events = $day.siblings('.event');
            if ($events.length == 0)
                $events = $day.siblings('.multiple-container').find('.event');
        }

        if (settings.eventsPosition == 'tooltip') {
            var $events = $this.find('div.event').not('.multiple');
            setTooltip($events, $this, settings.enter, settings.exit, settings.tooltip, settings.twitter, settings.tooltipPosition, settings.show, settings.timeFormat, settings.tooltipDelay, settings.color, settings.base, settings.editable, settings.subscribe);
        } else if (settings.eventsPosition == 'lightBox') {
            console.log($events);
            setLightBox($events);
        }
    });

}

function setLightBox($events) {

}

// set up tootips with event data 
function createTooltip($this, $that, timeFormat, tooltipPosition, editable, color, base, twitter, show, delay, exit, enter, tooltip, type, multiID, child, subscribe) {
    var id = $that.attr('data-id');
    var title = $that.attr('data-title');
    var location = $that.attr('data-location');
    var date = $that.attr('data-date').split('/');
    var time = $that.attr('data-time').split(':');
    var icon = $that.attr('data-icon');
    var text = $that.attr('data-txt');
    var oposite = '';
    var tFormat;
    var tHour;
    var tFormat;

    if (timeFormat == 'hrs') {
        tFormat = 'hrs';
        tHour = $that.attr('data-time');
    } else {
        var hours = time[0];
        var minutes = time[1];
        var tFormat = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12;
        tHour = hours + ':' + minutes;
    }
    if (tooltipPosition == 'top')
        oposite = 'bottom';
    else if (tooltipPosition == 'bottom')
        oposite = 'top';
    else if (tooltipPosition == 'left')
        oposite = 'right';
    else
        oposite = 'left';

    var txtbutton = twitter ? '<a title="tweet" data-title="' + title + '" href="#" class="tweet" target="_blank"><i class="fa fa-twitter" style="color:' + color + '"></i></a>' : '';
    var txtbuttonSusbscribe = subscribe ? '<i class="fa fa-bookmark subscribe" data-event="' + id + '" data-title="' + title + '" style="color:' + color + '"></i>' : '';

    date[1] = (parseInt(date[1]) - 1).toString();

    var $found = child ? $that : $this.find("td[data-month='" + date[1] + "'][data-year='" + date[2] + "'] a:containsNumber(" + date[0] + ")").data({
        title: title,
        location: location
    });
    var delButton = editable ? '<i title="delete" data-multiID="' + multiID + '" data-id="' + id + '" class="' + type + ' delete fa fa-trash" style="color:' + color + '"></i>' : '';

    $found.qtip({
        prerender: true,
        id: 'ev-' + id,
        content: {
            text: '<div class="tooltip-body">' + _.unescape(text) + '</div>' +
                '<div class="tooltip-hours">' + tHour + ' ' + tFormat + '</div>' +
                '<div class="tooltip-actions">' + '' + '<i title="' + location + '" data-location="' + location + '" class="location fa fa-map-marker" style="color:' + color + '"></i>' + txtbutton + txtbuttonSusbscribe + '</div>' +
                '<div class="count"><div>',

            title: {
                text: title,
                button: 'Close'
            }
        },
        position: {
            my: oposite + ' center',
            at: tooltipPosition + ' center'
        },
        show: {
            event: show
        },
        hide: {
            fixed: true,
            event: 'click',
            delay: 2000
        },
        style: {
            classes: 'draggable-tooltip qtip-shadow ' + base + ' qtip-' + tooltip
        },
        events: {
            show: function (el) {
                $('.qtip-titlebar').css('background-color', color);
                $(el.currentTarget).hide().fadeIn(1000);
                $(el.currentTarget).removeClass(exit).addClass(enter + ' animate');
                var until = new Date(date[2], date[1], date[0], time[0], time[1]);
                $('.count div').countdown('destroy');
                $('.count div').countdown({
                    until: until,
                    layout: '{dn} {dl} {hnn}{sep}{mnn}{sep}{snn}'
                });
            },
            hide: function (el) {
                $(el.currentTarget).show();
                $(el.currentTarget).removeClass(enter).addClass(exit).fadeOut(1000);
            },
            render: function (event, api) {
                $(this).draggable({
                    containment: 'window',
                    handle: api.elements.titlebar
                });
            }
        }
    });
}

// set up tooltip configuration
function setTooltip($events, $this, enter, exit, tooltip, twitter, tooltipPosition, show, timeFormat, delay, color, base, editable, suscribe) {
    $events.each(function () {
        createTooltip($this, $(this), timeFormat, tooltipPosition, editable, color, base, twitter, show, delay, exit, enter, tooltip, 'single', '', false, suscribe);
    });
    var $multiple = $this.find('div.multiple-container');
    $multiple.each(function (i) {
        var mID = 'multi-' + i;
        var $this = $(this);
        var $found = $this.siblings('a');
        var $mEvent = $this.find('div.multiple').eq(0);
        var mDate = $mEvent.attr('data-date');
        var oposite = '';

        if (tooltipPosition == 'top')
            oposite = 'bottom';
        else if (tooltipPosition == 'bottom')
            oposite = 'top';
        else if (tooltipPosition == 'left')
            oposite = 'right';
        else
            oposite = 'left';

        $found.qtip({
            id: mID,
            content: {
                text: '<div class="tooltip-body">' + $this.html() + '</div>',
                title: {
                    text: 'Events on ' + mDate,
                    button: 'Close'
                }
            },
            position: {
                my: oposite + ' center',
                at: tooltipPosition + ' center'
            },
            show: {
                event: show
            },
            hide: {
                fixed: true,
                delay: 2000
            },
            style: {
                classes: 'tooltip-multiple draggable-tooltip qtip-shadow ' + base + ' qtip-' + tooltip
            },
            events: {
                show: function (el) {
                    $('.qtip-titlebar').css('background-color', color);
                    $(el.currentTarget).hide().fadeIn(1000);
                    $(el.currentTarget).removeClass(exit).addClass(enter + ' animate');

                    var $eEvents = $(el.currentTarget).find('.multiple');
                    $eEvents.each(function () {
                        createTooltip($this, $(this), timeFormat, tooltipPosition, editable, color, base, twitter, show, delay, exit, enter, tooltip, 'multi', mID, true, suscribe);
                    });
                },
                hide: function (el) {
                    $(el.currentTarget).show();
                    $(el.currentTarget).removeClass(enter).addClass(exit).fadeOut(1000);
                },
                render: function (event, api) {
                    $(this).draggable({
                        containment: 'window',
                        handle: api.elements.titlebar
                    });
                }
            }
        });
    });
}

/*!
 * jQuery Twitter Search Plugin
 * Examples and documentation at: http://jquery.malsup.com/twitter/
 * Copyright (c) 2010 M. Alsup
 * Version: 1.01 (14-APR-2010)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.3.2 or later
 */

// @todo: refresh button

(function ($) {
    $.fn.twitterSearch = function (options) {
        if (typeof options == 'string')
            options = {
                term: options
            };
        return this.each(function () {
            var $frame = $(this);
            var opts = $.extend(true, {}, $.fn.twitterSearch.defaults, options || {}, $.metadata ? $frame.metadata() : {});
            opts.formatter = opts.formatter || $.fn.twitterSearch.formatter;
            opts.filter = opts.filter || $.fn.twitterSearch.filter;
            var url = opts.url + opts.term;

            if (!opts.applyStyles) { // throw away all style defs
                for (var css in opts.css)
                    opts.css[css] = {};
            }

            if (opts.title === null) // user can set to '' to suppress title
                opts.title = opts.term;

            opts.title = opts.title || '';
            var t = opts.titleLink ? ('<a href="' + opts.titleLink + '">' + opts.title + '</a>') : ('<span>' + opts.title + '</span>');
            var $t = $(t);
            if (opts.titleLink)
                $t.css(opts.css['titleLink']);

            var $title = $('<div class="twitterSearchTitle"></div>').append($t).appendTo($frame).css(opts.css['title']);
            if (opts.bird) {
                var $b = $title.css(opts.css['bird']);
                if (opts.birdLink)
                    $b.wrap('<a href="' + opts.birdLink + '"></a>');
            }
            var $cont = $('<div class="twitterSearchContainter"></div>').appendTo($frame).css(opts.css['container']);
            var $close = $('<div class="twitterSearchClose"></div>').appendTo($frame);
            var cont = $cont[0];
            if (opts.colorExterior)
                $title.css('background-color', opts.colorExterior);
            if (opts.colorInterior)
                $cont.css('background-color', opts.colorInterior);

            $frame.css(opts.css['frame']);
            if (opts.colorExterior)
                $frame.css('border-color', opts.colorExterior);

            var h = $frame.innerHeight() - $title.outerHeight();
            //$cont.height(h);

            if (opts.pause)
                $cont.hover(function () {
                    cont.twitterSearchPause = true;
                }, function () {
                    cont.twitterSearchPause = false;
                });

            $('<div class="twitterSearchLoading">Loading tweets..</div>').css(opts.css['loading']).appendTo($cont);

            // grab twitter stream
            $.ajax({
                url: opts.url,
                timeout: 30000,
                dataType: 'json'
            }).fail(function (xhr, status, e) {
                failWhale(e);
            }).done(function (json) {
                if (json.error) {
                    failWhale(json.error);
                    return;
                }
                $cont.empty();
                $.each(json.statuses, function (i) {
                    if (!opts.filter.call(opts, this))
                        return; // skip this tweet
                    var tweet = opts.formatter(this, opts),
                        $tweet = $(tweet);
                    $tweet.css(opts.css['tweet']);
                    var $img = $tweet.find('.twitterSearchProfileImg').css(opts.css['img']);
                    $tweet.find('.twitterSearchUser').css(opts.css['user']);
                    $tweet.find('.twitterSearchTime').css(opts.css['time']);
                    $tweet.find('a').css(opts.css['a']);
                    $tweet.appendTo($cont);
                    var $text = $tweet.find('.twitterSearchText').css(opts.css['text']);
                    if (opts.avatar) {
                        var w = $img.outerWidth() + parseInt($tweet.css('paddingLeft'));
                        $text.css('paddingLeft', w);
                    }
                });

                if (json.statuses.length < 2)
                    return;

                // stage first animation
                setTimeout(go, opts.timeout);
            });

            function failWhale(msg) {
                var $fail = $('<div class="twitterSearchFail">' + msg + '</div>').css(opts.css['fail']);
                $cont.empty().append($fail);
            };

            function go() {
                if (cont.twitterSearchPause) {
                    setTimeout(go, 500);
                    return;
                }
                var $el = $cont.children(':first'),
                    el = $el[0];
                $el.animate(opts.animOut, opts.animOutSpeed, function () {
                    var h = $el.outerHeight();
                    $el.animate({
                        marginTop: -h
                    }, opts.animInSpeed, function () {
                        $el.css({
                            marginTop: 0,
                            opacity: 1
                        });
                        /*@cc_on
						try { el.style.removeAttribute('filter'); } // ie cleartype fix
						catch(smother) {}
						@*/
                        $el.css(opts.css['tweet']).show().appendTo($cont);
                    });
                    // stage next animation
                    setTimeout(go, opts.timeout);
                });
            }
        });
    };

    $.fn.twitterSearch.filter = function (tweet) {
        return true;
    };

    $.fn.twitterSearch.formatter = function (json, opts) {
        var t = json.text;
        if (opts.anchors) {
            t = json.text.replace(/(http:\/\/\S+)/g, '<a target="_blank" href="$1">$1</a>');
            t = t.replace(/\@(\w+)/g, '<a style="color:' + opts.colorExterior + '" target="_blank" href="http://twitter.com/$1">@$1</a>');
        }
        //https://twitter.com/search/realtime?q=clumsypig
        var s = '<div class="twitterSearchTweet">';
        if (opts.avatar)
            s += '<img class="twitterSearchProfileImg" src="' + json.user.profile_image_url + '" />';
        s += '<div><span class="twitterSearchUser"><a style="color:' + opts.colorExterior + '" target="_blank" href="https://twitter.com/search/realtime?q=' + json.user.screen_name + '">' + json.user.name + ' @' + json.user.screen_name + '</a></span>';
        var d = prettyDate(json.created_at);
        if (opts.time && d)
            s += ' <span class="twitterSearchTime">(' + d + ')</span>'
        s += '<div class="twitterSearchText">' + t + '</div></div></div>';
        return s;
    };

    $.fn.twitterSearch.defaults = {
        url: 'http://search.twitter.com/search.json?callback=?&q=',
        anchors: true, // true or false (enable embedded links in tweets)
        animOutSpeed: 700, // speed of animation for top tweet when removed
        animInSpeed: 700, // speed of scroll animation for moving tweets up
        animOut: {
            opacity: 0
        }, // animation of top tweet when it is removed
        applyStyles: true, // true or false (apply default css styling or not)
        avatar: true, // true or false (show or hide twitter profile images)
        bird: true, // true or false (show or hide twitter bird image)
        birdLink: false, // url that twitter bird image should like to
        birdSrc: 'http://cloud.github.com/downloads/malsup/twitter/tweet.gif', // twitter bird image
        colorExterior: null, // css override of frame border-color and title background-color
        colorInterior: null, // css override of container background-color
        filter: null, // callback fn to filter tweets:  fn(tweetJson) { /* return false to skip tweet */ }
        formatter: null, // callback fn to build tweet markup
        pause: false, // true or false (pause on hover)
        term: '', // twitter search term
        time: true, // true or false (show or hide the time that the tweet was sent)
        timeout: 8000, // delay betweet tweet scroll
        title: null, // title text to display when frame option is true (default = 'term' text)
        titleLink: null, // url for title link
        css: {
            // default styling
            a: {
                textDecoration: 'none'
            },
            bird: {
                width: '50px',
                height: '20px',
                position: 'absolute',
                left: '-30px',
                top: '-20px',
                border: 'none'
            },
            container: {
                overflow: 'hidden',
                backgroundColor: '#FFF',
                height: '253px',
                border: '1px solid #ccc'
            },
            fail: {
                background: '#6cc5c3 url(http://cloud.github.com/downloads/malsup/twitter/failwhale.png) no-repeat 50% 50%',
                height: '100%',
                padding: '10px'
            },
            frame: {
                height: '280px',
                border: '5px solid #C2CFF1',
                '-moz-box-shadow': '1px 1px 5px #000',
                '-webkit-box-shadow': '1px 1px 5px #000'
            },
            tweet: {
                padding: '5px 10px',
                clear: 'left'
            },
            img: {
                'float': 'left',
                margin: '5px 10px 5px 0px',
                width: '48px',
                height: '48px',
                '-moz-box-shadow': '1px 1px 5px #000',
                '-webkit-box-shadow': '1px 1px 5px #000'
            },
            loading: {
                padding: '20px',
                textAlign: 'center',
                color: '#888',
                fontSize: '10px'
            },
            text: {
                fontSize: '11px',
                borderBottom: '1px solid #ddd',
                paddingBottom: '6px'
            },
            time: {
                fontSize: '10px',
                color: '#888'
            },
            title: {},
            titleLink: {
                textDecoration: 'none'
            },
            user: {
                fontWeight: 'bold',
                fontSize: '11px',
                textShadow: '1px 1px 1px #fff'
            }
        }
    };

    /*
     * JavaScript Pretty Date
     * Copyright (c) 2008 John Resig (jquery.com)
     * Licensed under the MIT license.
     */
    // converts ISO time to casual time
    function prettyDate(time) {
        var date = new Date((time || "").replace(/-/g, "/").replace(/TZ/g, " ")),
            diff = (((new Date()).getTime() - date.getTime()) / 1000),
            day_diff = Math.floor(diff / 86400);

        if (isNaN(day_diff) || day_diff < 0 || day_diff >= 31)
            return;
        var v = day_diff == 0 && (
                diff < 60 && "just now" ||
                diff < 120 && "1 minute ago" ||
                diff < 3600 && Math.floor(diff / 60) + " minutes ago" ||
                diff < 7200 && "1 hour ago" ||
                diff < 86400 && Math.floor(diff / 3600) + " hours ago") ||
            day_diff == 1 && "Yesterday" ||
            day_diff < 7 && day_diff + " days ago" ||
            day_diff < 31 && Math.ceil(day_diff / 7) + " weeks ago";
        if (!v)
            window.console && console.log(time);
        return v ? v : '';
    }

})(jQuery);

(function (window, $) {
    $.fn.twitterpopup = function (options) {
        var opts = $.extend({}, $.fn.twitterpopup.defaults, options);
        return this.each(function () {
            var $this = $(this);
            var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
            $this.on('click', function (e) {
                var $this = $(this);
                if ($this.data('active'))
                    return;
                var $search = $('<div class="search_results"></div>').appendTo(opts.container);
                //if (opts.modal)
                //$search.css('position','fixed');
                $search.twitterSearch({
                    term: $this.html(),
                    bird: false,
                    colorExterior: opts.colorExterior,
                    colorInterior: '#FFF',
                    pause: true,
                    //time			: false, 
                    timeout: 3000,
                    url: opts.url
                });
                var PopupPositions = $.fn.twitterpopup.calculatePopupPositions($this, $search);

                $search.resizable({
                    alsoResize: $search.find('.twitterSearchContainter'),
                    handles: 'se'
                }).draggable();
                $search.css({
                    left: (0),
                    top: ('82px')
                }).show();
                $this.data('active', true);
                $search.find('.twitterSearchClose').on('click', function () {
                    $search.remove();
                    $this.data('active', false);
                });
            });

        });
    };
    /*
	gets the current viewport width and height
	*/
    $.fn.twitterpopup.getWindowSize = function () {
        var WindowSize = {
            width: window.width(),
            height: window.height()
        };
        return WindowSize;
    };
    /*
	calculates left and top for the popup to be displayed, based on the viewport width and height
	*/
    $.fn.twitterpopup.calculatePopupPositions = function ($elem, $popup) {
        var WindowSize = $.fn.twitterpopup.getWindowSize();

        /* defaults sould be: */
        var popupL = $elem.offset().left + $elem.width() + 20;
        var popupT = $elem.offset().top;

        /* if final left+width of popup exceeds window width then popup should be placed on the left side */
        var popupWidth = $popup.width();
        if (popupL + popupWidth > WindowSize.width)
            popupL = $elem.offset().left - popupWidth - 20;

        /* if final top+height of popup exceeds window height then popup should be adjusted to fit the window */
        var $elemOffsetTop = $elem.offset().top - window.scrollTop();
        var popupHeight = $popup.height();

        /* cases: 
			1) when popup would be hidden on top of viewport 
			2) when popup would be hidden on bottom of viewport 
		*/
        if ($elemOffsetTop < 0) {
            popupT = $elem.offset().top - $elemOffsetTop;
        } else if ($elemOffsetTop + popupHeight > WindowSize.height) {
            var diff = $elemOffsetTop + popupHeight - WindowSize.height;
            popupT = $elem.offset().top - diff - 20;
        }

        /* new popup positions */
        var PopupPositions = {
            left: popupL,
            top: popupT
        };

        return PopupPositions;
    };

})(jQuery(window), jQuery);

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


/* http://keith-wood.name/countdown.html
   Countdown for jQuery v1.6.3.
   Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
   Available under the MIT (https://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt) license. 
   Please attribute the author if you use it. */
(function ($) {
    function Countdown() {
        this.regional = [];
        this.regional[''] = {
            labels: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'],
            labels1: ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'],
            compactLabels: ['y', 'm', 'w', 'd'],
            whichLabels: null,
            digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            timeSeparator: ':',
            isRTL: false
        };
        this._defaults = {
            until: null,
            since: null,
            timezone: null,
            serverSync: null,
            format: 'dHMS',
            layout: '',
            compact: false,
            significant: 0,
            description: '',
            expiryUrl: '',
            expiryText: '',
            alwaysExpire: false,
            onExpiry: null,
            onTick: null,
            tickInterval: 1
        };
        $.extend(this._defaults, this.regional['']);
        this._serverSyncs = [];
        var c = (typeof Date.now == 'function' ? Date.now : function () {
            return new Date().getTime()
        });
        var d = (window.performance && typeof window.performance.now == 'function');

        function timerCallBack(a) {
            var b = (a < 1e12 ? (d ? (performance.now() + performance.timing.navigationStart) : c()) : a || c());
            if (b - f >= 1000) {
                x._updateTargets();
                f = b
            }
            e(timerCallBack)
        }
        var e = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || null;
        var f = 0;
        if (!e || $.noRequestAnimationFrame) {
            $.noRequestAnimationFrame = null;
            setInterval(function () {
                x._updateTargets()
            }, 980)
        } else {
            f = window.animationStartTime || window.webkitAnimationStartTime || window.mozAnimationStartTime || window.oAnimationStartTime || window.msAnimationStartTime || c();
            e(timerCallBack)
        }
    }
    var Y = 0;
    var O = 1;
    var W = 2;
    var D = 3;
    var H = 4;
    var M = 5;
    var S = 6;
    $.extend(Countdown.prototype, {
        markerClassName: 'hasCountdown',
        propertyName: 'countdown',
        _rtlClass: 'countdown_rtl',
        _sectionClass: 'countdown_section',
        _amountClass: 'countdown_amount',
        _rowClass: 'countdown_row',
        _holdingClass: 'countdown_holding',
        _showClass: 'countdown_show',
        _descrClass: 'countdown_descr',
        _timerTargets: [],
        setDefaults: function (a) {
            this._resetExtraLabels(this._defaults, a);
            $.extend(this._defaults, a || {})
        },
        UTCDate: function (a, b, c, e, f, g, h, i) {
            if (typeof b == 'object' && b.constructor == Date) {
                i = b.getMilliseconds();
                h = b.getSeconds();
                g = b.getMinutes();
                f = b.getHours();
                e = b.getDate();
                c = b.getMonth();
                b = b.getFullYear()
            }
            var d = new Date();
            d.setUTCFullYear(b);
            d.setUTCDate(1);
            d.setUTCMonth(c || 0);
            d.setUTCDate(e || 1);
            d.setUTCHours(f || 0);
            d.setUTCMinutes((g || 0) - (Math.abs(a) < 30 ? a * 60 : a));
            d.setUTCSeconds(h || 0);
            d.setUTCMilliseconds(i || 0);
            return d
        },
        periodsToSeconds: function (a) {
            return a[0] * 31557600 + a[1] * 2629800 + a[2] * 604800 + a[3] * 86400 + a[4] * 3600 + a[5] * 60 + a[6]
        },
        _attachPlugin: function (a, b) {
            a = $(a);
            if (a.hasClass(this.markerClassName)) {
                return
            }
            var c = {
                options: $.extend({}, this._defaults),
                _periods: [0, 0, 0, 0, 0, 0, 0]
            };
            a.addClass(this.markerClassName).data(this.propertyName, c);
            this._optionPlugin(a, b)
        },
        _addTarget: function (a) {
            if (!this._hasTarget(a)) {
                this._timerTargets.push(a)
            }
        },
        _hasTarget: function (a) {
            return ($.inArray(a, this._timerTargets) > -1)
        },
        _removeTarget: function (b) {
            this._timerTargets = $.map(this._timerTargets, function (a) {
                return (a == b ? null : a)
            })
        },
        _updateTargets: function () {
            for (var i = this._timerTargets.length - 1; i >= 0; i--) {
                this._updateCountdown(this._timerTargets[i])
            }
        },
        _optionPlugin: function (a, b, c) {
            a = $(a);
            var d = a.data(this.propertyName);
            if (!b || (typeof b == 'string' && c == null)) {
                var e = b;
                b = (d || {}).options;
                return (b && e ? b[e] : b)
            }
            if (!a.hasClass(this.markerClassName)) {
                return
            }
            b = b || {};
            if (typeof b == 'string') {
                var e = b;
                b = {};
                b[e] = c
            }
            if (b.layout) {
                b.layout = b.layout.replace(/&lt;/g, '<').replace(/&gt;/g, '>')
            }
            this._resetExtraLabels(d.options, b);
            var f = (d.options.timezone != b.timezone);
            $.extend(d.options, b);
            this._adjustSettings(a, d, b.until != null || b.since != null || f);
            var g = new Date();
            if ((d._since && d._since < g) || (d._until && d._until > g)) {
                this._addTarget(a[0])
            }
            this._updateCountdown(a, d)
        },
        _updateCountdown: function (a, b) {
            var c = $(a);
            b = b || c.data(this.propertyName);
            if (!b) {
                return
            }
            c.html(this._generateHTML(b)).toggleClass(this._rtlClass, b.options.isRTL);
            if ($.isFunction(b.options.onTick)) {
                var d = b._hold != 'lap' ? b._periods : this._calculatePeriods(b, b._show, b.options.significant, new Date());
                if (b.options.tickInterval == 1 || this.periodsToSeconds(d) % b.options.tickInterval == 0) {
                    b.options.onTick.apply(a, [d])
                }
            }
            var e = b._hold != 'pause' && (b._since ? b._now.getTime() < b._since.getTime() : b._now.getTime() >= b._until.getTime());
            if (e && !b._expiring) {
                b._expiring = true;
                if (this._hasTarget(a) || b.options.alwaysExpire) {
                    this._removeTarget(a);
                    if ($.isFunction(b.options.onExpiry)) {
                        b.options.onExpiry.apply(a, [])
                    }
                    if (b.options.expiryText) {
                        var f = b.options.layout;
                        b.options.layout = b.options.expiryText;
                        this._updateCountdown(a, b);
                        b.options.layout = f
                    }
                    if (b.options.expiryUrl) {
                        window.location = b.options.expiryUrl
                    }
                }
                b._expiring = false
            } else if (b._hold == 'pause') {
                this._removeTarget(a)
            }
            c.data(this.propertyName, b)
        },
        _resetExtraLabels: function (a, b) {
            var c = false;
            for (var n in b) {
                if (n != 'whichLabels' && n.match(/[Ll]abels/)) {
                    c = true;
                    break
                }
            }
            if (c) {
                for (var n in a) {
                    if (n.match(/[Ll]abels[02-9]|compactLabels1/)) {
                        a[n] = null
                    }
                }
            }
        },
        _adjustSettings: function (a, b, c) {
            var d;
            var e = 0;
            var f = null;
            for (var i = 0; i < this._serverSyncs.length; i++) {
                if (this._serverSyncs[i][0] == b.options.serverSync) {
                    f = this._serverSyncs[i][1];
                    break
                }
            }
            if (f != null) {
                e = (b.options.serverSync ? f : 0);
                d = new Date()
            } else {
                var g = ($.isFunction(b.options.serverSync) ? b.options.serverSync.apply(a, []) : null);
                d = new Date();
                e = (g ? d.getTime() - g.getTime() : 0);
                this._serverSyncs.push([b.options.serverSync, e])
            }
            var h = b.options.timezone;
            h = (h == null ? -d.getTimezoneOffset() : h);
            if (c || (!c && b._until == null && b._since == null)) {
                b._since = b.options.since;
                if (b._since != null) {
                    b._since = this.UTCDate(h, this._determineTime(b._since, null));
                    if (b._since && e) {
                        b._since.setMilliseconds(b._since.getMilliseconds() + e)
                    }
                }
                b._until = this.UTCDate(h, this._determineTime(b.options.until, d));
                if (e) {
                    b._until.setMilliseconds(b._until.getMilliseconds() + e)
                }
            }
            b._show = this._determineShow(b)
        },
        _destroyPlugin: function (a) {
            a = $(a);
            if (!a.hasClass(this.markerClassName)) {
                return
            }
            this._removeTarget(a[0]);
            a.removeClass(this.markerClassName).empty().removeData(this.propertyName)
        },
        _pausePlugin: function (a) {
            this._hold(a, 'pause')
        },
        _lapPlugin: function (a) {
            this._hold(a, 'lap')
        },
        _resumePlugin: function (a) {
            this._hold(a, null)
        },
        _hold: function (a, b) {
            var c = $.data(a, this.propertyName);
            if (c) {
                if (c._hold == 'pause' && !b) {
                    c._periods = c._savePeriods;
                    var d = (c._since ? '-' : '+');
                    c[c._since ? '_since' : '_until'] = this._determineTime(d + c._periods[0] + 'y' + d + c._periods[1] + 'o' + d + c._periods[2] + 'w' + d + c._periods[3] + 'd' + d + c._periods[4] + 'h' + d + c._periods[5] + 'm' + d + c._periods[6] + 's');
                    this._addTarget(a)
                }
                c._hold = b;
                c._savePeriods = (b == 'pause' ? c._periods : null);
                $.data(a, this.propertyName, c);
                this._updateCountdown(a, c)
            }
        },
        _getTimesPlugin: function (a) {
            var b = $.data(a, this.propertyName);
            return (!b ? null : (b._hold == 'pause' ? b._savePeriods : (!b._hold ? b._periods : this._calculatePeriods(b, b._show, b.options.significant, new Date()))))
        },
        _determineTime: function (k, l) {
            var m = function (a) {
                var b = new Date();
                b.setTime(b.getTime() + a * 1000);
                return b
            };
            var n = function (a) {
                a = a.toLowerCase();
                var b = new Date();
                var c = b.getFullYear();
                var d = b.getMonth();
                var e = b.getDate();
                var f = b.getHours();
                var g = b.getMinutes();
                var h = b.getSeconds();
                var i = /([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;
                var j = i.exec(a);
                while (j) {
                    switch (j[2] || 's') {
                    case 's':
                        h += parseInt(j[1], 10);
                        break;
                    case 'm':
                        g += parseInt(j[1], 10);
                        break;
                    case 'h':
                        f += parseInt(j[1], 10);
                        break;
                    case 'd':
                        e += parseInt(j[1], 10);
                        break;
                    case 'w':
                        e += parseInt(j[1], 10) * 7;
                        break;
                    case 'o':
                        d += parseInt(j[1], 10);
                        e = Math.min(e, x._getDaysInMonth(c, d));
                        break;
                    case 'y':
                        c += parseInt(j[1], 10);
                        e = Math.min(e, x._getDaysInMonth(c, d));
                        break
                    }
                    j = i.exec(a)
                }
                return new Date(c, d, e, f, g, h, 0)
            };
            var o = (k == null ? l : (typeof k == 'string' ? n(k) : (typeof k == 'number' ? m(k) : k)));
            if (o) o.setMilliseconds(0);
            return o
        },
        _getDaysInMonth: function (a, b) {
            return 32 - new Date(a, b, 32).getDate()
        },
        _normalLabels: function (a) {
            return a
        },
        _generateHTML: function (c) {
            var d = this;
            c._periods = (c._hold ? c._periods : this._calculatePeriods(c, c._show, c.options.significant, new Date()));
            var e = false;
            var f = 0;
            var g = c.options.significant;
            var h = $.extend({}, c._show);
            for (var i = Y; i <= S; i++) {
                e |= (c._show[i] == '?' && c._periods[i] > 0);
                h[i] = (c._show[i] == '?' && !e ? null : c._show[i]);
                f += (h[i] ? 1 : 0);
                g -= (c._periods[i] > 0 ? 1 : 0)
            }
            var j = [false, false, false, false, false, false, false];
            for (var i = S; i >= Y; i--) {
                if (c._show[i]) {
                    if (c._periods[i]) {
                        j[i] = true
                    } else {
                        j[i] = g > 0;
                        g--
                    }
                }
            }
            var k = (c.options.compact ? c.options.compactLabels : c.options.labels);
            var l = c.options.whichLabels || this._normalLabels;
            var m = function (a) {
                var b = c.options['compactLabels' + l(c._periods[a])];
                return (h[a] ? d._translateDigits(c, c._periods[a]) + (b ? b[a] : k[a]) + ' ' : '')
            };
            var n = function (a) {
                var b = c.options['labels' + l(c._periods[a])];
                return ((!c.options.significant && h[a]) || (c.options.significant && j[a]) ? '<span class="' + x._sectionClass + '">' + '<span class="' + x._amountClass + '">' + d._translateDigits(c, c._periods[a]) + '</span><br/>' + (b ? b[a] : k[a]) + '</span>' : '')
            };
            return (c.options.layout ? this._buildLayout(c, h, c.options.layout, c.options.compact, c.options.significant, j) : ((c.options.compact ? '<span class="' + this._rowClass + ' ' + this._amountClass + (c._hold ? ' ' + this._holdingClass : '') + '">' + m(Y) + m(O) + m(W) + m(D) + (h[H] ? this._minDigits(c, c._periods[H], 2) : '') + (h[M] ? (h[H] ? c.options.timeSeparator : '') + this._minDigits(c, c._periods[M], 2) : '') + (h[S] ? (h[H] || h[M] ? c.options.timeSeparator : '') + this._minDigits(c, c._periods[S], 2) : '') : '<span class="' + this._rowClass + ' ' + this._showClass + (c.options.significant || f) + (c._hold ? ' ' + this._holdingClass : '') + '">' + n(Y) + n(O) + n(W) + n(D) + n(H) + n(M) + n(S)) + '</span>' + (c.options.description ? '<span class="' + this._rowClass + ' ' + this._descrClass + '">' + c.options.description + '</span>' : '')))
        },
        _buildLayout: function (c, d, e, f, g, h) {
            var j = c.options[f ? 'compactLabels' : 'labels'];
            var k = c.options.whichLabels || this._normalLabels;
            var l = function (a) {
                return (c.options[(f ? 'compactLabels' : 'labels') + k(c._periods[a])] || j)[a]
            };
            var m = function (a, b) {
                return c.options.digits[Math.floor(a / b) % 10]
            };
            var o = {
                desc: c.options.description,
                sep: c.options.timeSeparator,
                yl: l(Y),
                yn: this._minDigits(c, c._periods[Y], 1),
                ynn: this._minDigits(c, c._periods[Y], 2),
                ynnn: this._minDigits(c, c._periods[Y], 3),
                y1: m(c._periods[Y], 1),
                y10: m(c._periods[Y], 10),
                y100: m(c._periods[Y], 100),
                y1000: m(c._periods[Y], 1000),
                ol: l(O),
                on: this._minDigits(c, c._periods[O], 1),
                onn: this._minDigits(c, c._periods[O], 2),
                onnn: this._minDigits(c, c._periods[O], 3),
                o1: m(c._periods[O], 1),
                o10: m(c._periods[O], 10),
                o100: m(c._periods[O], 100),
                o1000: m(c._periods[O], 1000),
                wl: l(W),
                wn: this._minDigits(c, c._periods[W], 1),
                wnn: this._minDigits(c, c._periods[W], 2),
                wnnn: this._minDigits(c, c._periods[W], 3),
                w1: m(c._periods[W], 1),
                w10: m(c._periods[W], 10),
                w100: m(c._periods[W], 100),
                w1000: m(c._periods[W], 1000),
                dl: l(D),
                dn: this._minDigits(c, c._periods[D], 1),
                dnn: this._minDigits(c, c._periods[D], 2),
                dnnn: this._minDigits(c, c._periods[D], 3),
                d1: m(c._periods[D], 1),
                d10: m(c._periods[D], 10),
                d100: m(c._periods[D], 100),
                d1000: m(c._periods[D], 1000),
                hl: l(H),
                hn: this._minDigits(c, c._periods[H], 1),
                hnn: this._minDigits(c, c._periods[H], 2),
                hnnn: this._minDigits(c, c._periods[H], 3),
                h1: m(c._periods[H], 1),
                h10: m(c._periods[H], 10),
                h100: m(c._periods[H], 100),
                h1000: m(c._periods[H], 1000),
                ml: l(M),
                mn: this._minDigits(c, c._periods[M], 1),
                mnn: this._minDigits(c, c._periods[M], 2),
                mnnn: this._minDigits(c, c._periods[M], 3),
                m1: m(c._periods[M], 1),
                m10: m(c._periods[M], 10),
                m100: m(c._periods[M], 100),
                m1000: m(c._periods[M], 1000),
                sl: l(S),
                sn: this._minDigits(c, c._periods[S], 1),
                snn: this._minDigits(c, c._periods[S], 2),
                snnn: this._minDigits(c, c._periods[S], 3),
                s1: m(c._periods[S], 1),
                s10: m(c._periods[S], 10),
                s100: m(c._periods[S], 100),
                s1000: m(c._periods[S], 1000)
            };
            var p = e;
            for (var i = Y; i <= S; i++) {
                var q = 'yowdhms'.charAt(i);
                var r = new RegExp('\\{' + q + '<\\}([\\s\\S]*)\\{' + q + '>\\}', 'g');
                p = p.replace(r, ((!g && d[i]) || (g && h[i]) ? '$1' : ''))
            }
            $.each(o, function (n, v) {
                var a = new RegExp('\\{' + n + '\\}', 'g');
                p = p.replace(a, v)
            });
            return p
        },
        _minDigits: function (a, b, c) {
            b = '' + b;
            if (b.length >= c) {
                return this._translateDigits(a, b)
            }
            b = '0000000000' + b;
            return this._translateDigits(a, b.substr(b.length - c))
        },
        _translateDigits: function (b, c) {
            return ('' + c).replace(/[0-9]/g, function (a) {
                return b.options.digits[a]
            })
        },
        _determineShow: function (a) {
            var b = a.options.format;
            var c = [];
            c[Y] = (b.match('y') ? '?' : (b.match('Y') ? '!' : null));
            c[O] = (b.match('o') ? '?' : (b.match('O') ? '!' : null));
            c[W] = (b.match('w') ? '?' : (b.match('W') ? '!' : null));
            c[D] = (b.match('d') ? '?' : (b.match('D') ? '!' : null));
            c[H] = (b.match('h') ? '?' : (b.match('H') ? '!' : null));
            c[M] = (b.match('m') ? '?' : (b.match('M') ? '!' : null));
            c[S] = (b.match('s') ? '?' : (b.match('S') ? '!' : null));
            return c
        },
        _calculatePeriods: function (c, d, e, f) {
            c._now = f;
            c._now.setMilliseconds(0);
            var g = new Date(c._now.getTime());
            if (c._since) {
                if (f.getTime() < c._since.getTime()) {
                    c._now = f = g
                } else {
                    f = c._since
                }
            } else {
                g.setTime(c._until.getTime());
                if (f.getTime() > c._until.getTime()) {
                    c._now = f = g
                }
            }
            var h = [0, 0, 0, 0, 0, 0, 0];
            if (d[Y] || d[O]) {
                var i = x._getDaysInMonth(f.getFullYear(), f.getMonth());
                var j = x._getDaysInMonth(g.getFullYear(), g.getMonth());
                var k = (g.getDate() == f.getDate() || (g.getDate() >= Math.min(i, j) && f.getDate() >= Math.min(i, j)));
                var l = function (a) {
                    return (a.getHours() * 60 + a.getMinutes()) * 60 + a.getSeconds()
                };
                var m = Math.max(0, (g.getFullYear() - f.getFullYear()) * 12 + g.getMonth() - f.getMonth() + ((g.getDate() < f.getDate() && !k) || (k && l(g) < l(f)) ? -1 : 0));
                h[Y] = (d[Y] ? Math.floor(m / 12) : 0);
                h[O] = (d[O] ? m - h[Y] * 12 : 0);
                f = new Date(f.getTime());
                var n = (f.getDate() == i);
                var o = x._getDaysInMonth(f.getFullYear() + h[Y], f.getMonth() + h[O]);
                if (f.getDate() > o) {
                    f.setDate(o)
                }
                f.setFullYear(f.getFullYear() + h[Y]);
                f.setMonth(f.getMonth() + h[O]);
                if (n) {
                    f.setDate(o)
                }
            }
            var p = Math.floor((g.getTime() - f.getTime()) / 1000);
            var q = function (a, b) {
                h[a] = (d[a] ? Math.floor(p / b) : 0);
                p -= h[a] * b
            };
            q(W, 604800);
            q(D, 86400);
            q(H, 3600);
            q(M, 60);
            q(S, 1);
            if (p > 0 && !c._since) {
                var r = [1, 12, 4.3482, 7, 24, 60, 60];
                var s = S;
                var t = 1;
                for (var u = S; u >= Y; u--) {
                    if (d[u]) {
                        if (h[s] >= t) {
                            h[s] = 0;
                            p = 1
                        }
                        if (p > 0) {
                            h[u] ++;
                            p = 0;
                            s = u;
                            t = 1
                        }
                    }
                    t *= r[u]
                }
            }
            if (e) {
                for (var u = Y; u <= S; u++) {
                    if (e && h[u]) {
                        e--
                    } else if (!e) {
                        h[u] = 0
                    }
                }
            }
            return h
        }
    });
    var w = ['getTimes'];

    function isNotChained(a, b) {
        if (a == 'option' && (b.length == 0 || (b.length == 1 && typeof b[0] == 'string'))) {
            return true
        }
        return $.inArray(a, w) > -1
    }
    $.fn.countdown = function (a) {
        var b = Array.prototype.slice.call(arguments, 1);
        if (isNotChained(a, b)) {
            return x['_' + a + 'Plugin'].apply(x, [this[0]].concat(b))
        }
        return this.each(function () {
            if (typeof a == 'string') {
                if (!x['_' + a + 'Plugin']) {
                    throw 'Unknown command: ' + a;
                }
                x['_' + a + 'Plugin'].apply(x, [this].concat(b))
            } else {
                x._attachPlugin(this, a || {})
            }
        })
    };
    var x = $.countdown = new Countdown()
})(jQuery);

/* == jquery mousewheel plugin == Version: 3.1.12, License: MIT License (MIT) */
! function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? module.exports = a : a(jQuery)
}(function (a) {
    function b(b) {
        var g = b || window.event,
            h = i.call(arguments, 1),
            j = 0,
            l = 0,
            m = 0,
            n = 0,
            o = 0,
            p = 0;
        if (b = a.event.fix(g), b.type = "mousewheel", "detail" in g && (m = -1 * g.detail), "wheelDelta" in g && (m = g.wheelDelta), "wheelDeltaY" in g && (m = g.wheelDeltaY), "wheelDeltaX" in g && (l = -1 * g.wheelDeltaX), "axis" in g && g.axis === g.HORIZONTAL_AXIS && (l = -1 * m, m = 0), j = 0 === m ? l : m, "deltaY" in g && (m = -1 * g.deltaY, j = m), "deltaX" in g && (l = g.deltaX, 0 === m && (j = -1 * l)), 0 !== m || 0 !== l) {
            if (1 === g.deltaMode) {
                var q = a.data(this, "mousewheel-line-height");
                j *= q, m *= q, l *= q
            } else if (2 === g.deltaMode) {
                var r = a.data(this, "mousewheel-page-height");
                j *= r, m *= r, l *= r
            }
            if (n = Math.max(Math.abs(m), Math.abs(l)), (!f || f > n) && (f = n, d(g, n) && (f /= 40)), d(g, n) && (j /= 40, l /= 40, m /= 40), j = Math[j >= 1 ? "floor" : "ceil"](j / f), l = Math[l >= 1 ? "floor" : "ceil"](l / f), m = Math[m >= 1 ? "floor" : "ceil"](m / f), k.settings.normalizeOffset && this.getBoundingClientRect) {
                var s = this.getBoundingClientRect();
                o = b.clientX - s.left, p = b.clientY - s.top
            }
            return b.deltaX = l, b.deltaY = m, b.deltaFactor = f, b.offsetX = o, b.offsetY = p, b.deltaMode = 0, h.unshift(b, j, l, m), e && clearTimeout(e), e = setTimeout(c, 200), (a.event.dispatch || a.event.handle).apply(this, h)
        }
    }

    function c() {
        f = null
    }

    function d(a, b) {
        return k.settings.adjustOldDeltas && "mousewheel" === a.type && b % 120 === 0
    }
    var e, f, g = ["wheel", "mousewheel", "DOMMouseScroll", "MozMousePixelScroll"],
        h = "onwheel" in document || document.documentMode >= 9 ? ["wheel"] : ["mousewheel", "DomMouseScroll", "MozMousePixelScroll"],
        i = Array.prototype.slice;
    if (a.event.fixHooks)
        for (var j = g.length; j;) a.event.fixHooks[g[--j]] = a.event.mouseHooks;
    var k = a.event.special.mousewheel = {
        version: "3.1.12",
        setup: function () {
            if (this.addEventListener)
                for (var c = h.length; c;) this.addEventListener(h[--c], b, !1);
            else this.onmousewheel = b;
            a.data(this, "mousewheel-line-height", k.getLineHeight(this)), a.data(this, "mousewheel-page-height", k.getPageHeight(this))
        },
        teardown: function () {
            if (this.removeEventListener)
                for (var c = h.length; c;) this.removeEventListener(h[--c], b, !1);
            else this.onmousewheel = null;
            a.removeData(this, "mousewheel-line-height"), a.removeData(this, "mousewheel-page-height")
        },
        getLineHeight: function (b) {
            var c = a(b),
                d = c["offsetParent" in a.fn ? "offsetParent" : "parent"]();
            return d.length || (d = a("body")), parseInt(d.css("fontSize"), 10) || parseInt(c.css("fontSize"), 10) || 16
        },
        getPageHeight: function (b) {
            return a(b).height()
        },
        settings: {
            adjustOldDeltas: !0,
            normalizeOffset: !0
        }
    };
    a.fn.extend({
        mousewheel: function (a) {
            return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
        },
        unmousewheel: function (a) {
            return this.unbind("mousewheel", a)
        }
    })
});
/* == malihu jquery custom scrollbar plugin == Version: 3.0.8, License: MIT License (MIT) */
! function (e) {
    "undefined" != typeof module && module.exports ? module.exports = e : e(jQuery, window, document)
}(function (e) {
    ! function (t) {
        var o = "function" == typeof define && define.amd,
            a = "undefined" != typeof module && module.exports,
            n = "https:" == document.location.protocol ? "https:" : "http:",
            i = "cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.12/jquery.mousewheel.min.js";
        o || (a ? require("jquery-mousewheel")(e) : e.event.special.mousewheel || e("head").append(decodeURI("%3Cscript src=" + n + "//" + i + "%3E%3C/script%3E"))), t()
    }(function () {
        var t, o = "mCustomScrollbar",
            a = "mCS",
            n = ".mCustomScrollbar",
            i = {
                setTop: 0,
                setLeft: 0,
                axis: "y",
                scrollbarPosition: "inside",
                scrollInertia: 950,
                autoDraggerLength: !0,
                alwaysShowScrollbar: 0,
                snapOffset: 0,
                mouseWheel: {
                    enable: !0,
                    scrollAmount: "auto",
                    axis: "y",
                    deltaFactor: "auto",
                    disableOver: ["select", "option", "keygen", "datalist", "textarea"]
                },
                scrollButtons: {
                    scrollType: "stepless",
                    scrollAmount: "auto"
                },
                keyboard: {
                    enable: !0,
                    scrollType: "stepless",
                    scrollAmount: "auto"
                },
                contentTouchScroll: 25,
                advanced: {
                    autoScrollOnFocus: "input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",
                    updateOnContentResize: !0,
                    updateOnImageLoad: !0
                },
                theme: "light",
                callbacks: {
                    onTotalScrollOffset: 0,
                    onTotalScrollBackOffset: 0,
                    alwaysTriggerOffsets: !0
                }
            },
            r = 0,
            l = {},
            s = window.attachEvent && !window.addEventListener ? 1 : 0,
            c = !1,
            d = ["mCSB_dragger_onDrag", "mCSB_scrollTools_onDrag", "mCS_img_loaded", "mCS_disabled", "mCS_destroyed", "mCS_no_scrollbar", "mCS-autoHide", "mCS-dir-rtl", "mCS_no_scrollbar_y", "mCS_no_scrollbar_x", "mCS_y_hidden", "mCS_x_hidden", "mCSB_draggerContainer", "mCSB_buttonUp", "mCSB_buttonDown", "mCSB_buttonLeft", "mCSB_buttonRight"],
            u = {
                init: function (t) {
                    var t = e.extend(!0, {}, i, t),
                        o = f.call(this);
                    if (t.live) {
                        var s = t.liveSelector || this.selector || n,
                            c = e(s);
                        if ("off" === t.live) return void m(s);
                        l[s] = setTimeout(function () {
                            c.mCustomScrollbar(t), "once" === t.live && c.length && m(s)
                        }, 500)
                    } else m(s);
                    return t.setWidth = t.set_width ? t.set_width : t.setWidth, t.setHeight = t.set_height ? t.set_height : t.setHeight, t.axis = t.horizontalScroll ? "x" : p(t.axis), t.scrollInertia = t.scrollInertia > 0 && t.scrollInertia < 17 ? 17 : t.scrollInertia, "object" != typeof t.mouseWheel && 1 == t.mouseWheel && (t.mouseWheel = {
                        enable: !0,
                        scrollAmount: "auto",
                        axis: "y",
                        preventDefault: !1,
                        deltaFactor: "auto",
                        normalizeDelta: !1,
                        invert: !1
                    }), t.mouseWheel.scrollAmount = t.mouseWheelPixels ? t.mouseWheelPixels : t.mouseWheel.scrollAmount, t.mouseWheel.normalizeDelta = t.advanced.normalizeMouseWheelDelta ? t.advanced.normalizeMouseWheelDelta : t.mouseWheel.normalizeDelta, t.scrollButtons.scrollType = g(t.scrollButtons.scrollType), h(t), e(o).each(function () {
                        var o = e(this);
                        if (!o.data(a)) {
                            o.data(a, {
                                idx: ++r,
                                opt: t,
                                scrollRatio: {
                                    y: null,
                                    x: null
                                },
                                overflowed: null,
                                contentReset: {
                                    y: null,
                                    x: null
                                },
                                bindEvents: !1,
                                tweenRunning: !1,
                                sequential: {},
                                langDir: o.css("direction"),
                                cbOffsets: null,
                                trigger: null
                            });
                            var n = o.data(a),
                                i = n.opt,
                                l = o.data("mcs-axis"),
                                s = o.data("mcs-scrollbar-position"),
                                c = o.data("mcs-theme");
                            l && (i.axis = l), s && (i.scrollbarPosition = s), c && (i.theme = c, h(i)), v.call(this), e("#mCSB_" + n.idx + "_container img:not(." + d[2] + ")").addClass(d[2]), u.update.call(null, o)
                        }
                    })
                },
                update: function (t, o) {
                    var n = t || f.call(this);
                    return e(n).each(function () {
                        var t = e(this);
                        if (t.data(a)) {
                            var n = t.data(a),
                                i = n.opt,
                                r = e("#mCSB_" + n.idx + "_container"),
                                l = [e("#mCSB_" + n.idx + "_dragger_vertical"), e("#mCSB_" + n.idx + "_dragger_horizontal")];
                            if (!r.length) return;
                            n.tweenRunning && V(t), t.hasClass(d[3]) && t.removeClass(d[3]), t.hasClass(d[4]) && t.removeClass(d[4]), S.call(this), _.call(this), "y" === i.axis || i.advanced.autoExpandHorizontalScroll || r.css("width", x(r.children())), n.overflowed = B.call(this), O.call(this), i.autoDraggerLength && b.call(this), C.call(this), k.call(this);
                            var s = [Math.abs(r[0].offsetTop), Math.abs(r[0].offsetLeft)];
                            "x" !== i.axis && (n.overflowed[0] ? l[0].height() > l[0].parent().height() ? T.call(this) : (Q(t, s[0].toString(), {
                                dir: "y",
                                dur: 0,
                                overwrite: "none"
                            }), n.contentReset.y = null) : (T.call(this), "y" === i.axis ? M.call(this) : "yx" === i.axis && n.overflowed[1] && Q(t, s[1].toString(), {
                                dir: "x",
                                dur: 0,
                                overwrite: "none"
                            }))), "y" !== i.axis && (n.overflowed[1] ? l[1].width() > l[1].parent().width() ? T.call(this) : (Q(t, s[1].toString(), {
                                dir: "x",
                                dur: 0,
                                overwrite: "none"
                            }), n.contentReset.x = null) : (T.call(this), "x" === i.axis ? M.call(this) : "yx" === i.axis && n.overflowed[0] && Q(t, s[0].toString(), {
                                dir: "y",
                                dur: 0,
                                overwrite: "none"
                            }))), o && n && (2 === o && i.callbacks.onImageLoad && "function" == typeof i.callbacks.onImageLoad ? i.callbacks.onImageLoad.call(this) : 3 === o && i.callbacks.onSelectorChange && "function" == typeof i.callbacks.onSelectorChange ? i.callbacks.onSelectorChange.call(this) : i.callbacks.onUpdate && "function" == typeof i.callbacks.onUpdate && i.callbacks.onUpdate.call(this)), X.call(this)
                        }
                    })
                },
                scrollTo: function (t, o) {
                    if ("undefined" != typeof t && null != t) {
                        var n = f.call(this);
                        return e(n).each(function () {
                            var n = e(this);
                            if (n.data(a)) {
                                var i = n.data(a),
                                    r = i.opt,
                                    l = {
                                        trigger: "external",
                                        scrollInertia: r.scrollInertia,
                                        scrollEasing: "mcsEaseInOut",
                                        moveDragger: !1,
                                        timeout: 60,
                                        callbacks: !0,
                                        onStart: !0,
                                        onUpdate: !0,
                                        onComplete: !0
                                    },
                                    s = e.extend(!0, {}, l, o),
                                    c = Y.call(this, t),
                                    d = s.scrollInertia > 0 && s.scrollInertia < 17 ? 17 : s.scrollInertia;
                                c[0] = j.call(this, c[0], "y"), c[1] = j.call(this, c[1], "x"), s.moveDragger && (c[0] *= i.scrollRatio.y, c[1] *= i.scrollRatio.x), s.dur = d, setTimeout(function () {
                                    null !== c[0] && "undefined" != typeof c[0] && "x" !== r.axis && i.overflowed[0] && (s.dir = "y", s.overwrite = "all", Q(n, c[0].toString(), s)), null !== c[1] && "undefined" != typeof c[1] && "y" !== r.axis && i.overflowed[1] && (s.dir = "x", s.overwrite = "none", Q(n, c[1].toString(), s))
                                }, s.timeout)
                            }
                        })
                    }
                },
                stop: function () {
                    var t = f.call(this);
                    return e(t).each(function () {
                        var t = e(this);
                        t.data(a) && V(t)
                    })
                },
                disable: function (t) {
                    var o = f.call(this);
                    return e(o).each(function () {
                        var o = e(this);
                        if (o.data(a)) {
                            {
                                o.data(a)
                            }
                            X.call(this, "remove"), M.call(this), t && T.call(this), O.call(this, !0), o.addClass(d[3])
                        }
                    })
                },
                destroy: function () {
                    var t = f.call(this);
                    return e(t).each(function () {
                        var n = e(this);
                        if (n.data(a)) {
                            var i = n.data(a),
                                r = i.opt,
                                l = e("#mCSB_" + i.idx),
                                s = e("#mCSB_" + i.idx + "_container"),
                                c = e(".mCSB_" + i.idx + "_scrollbar");
                            r.live && m(r.liveSelector || e(t).selector), X.call(this, "remove"), M.call(this), T.call(this), n.removeData(a), Z(this, "mcs"), c.remove(), s.find("img." + d[2]).removeClass(d[2]), l.replaceWith(s.contents()), n.removeClass(o + " _" + a + "_" + i.idx + " " + d[6] + " " + d[7] + " " + d[5] + " " + d[3]).addClass(d[4])
                        }
                    })
                }
            },
            f = function () {
                return "object" != typeof e(this) || e(this).length < 1 ? n : this
            },
            h = function (t) {
                var o = ["rounded", "rounded-dark", "rounded-dots", "rounded-dots-dark"],
                    a = ["rounded-dots", "rounded-dots-dark", "3d", "3d-dark", "3d-thick", "3d-thick-dark", "inset", "inset-dark", "inset-2", "inset-2-dark", "inset-3", "inset-3-dark"],
                    n = ["minimal", "minimal-dark"],
                    i = ["minimal", "minimal-dark"],
                    r = ["minimal", "minimal-dark"];
                t.autoDraggerLength = e.inArray(t.theme, o) > -1 ? !1 : t.autoDraggerLength, t.autoExpandScrollbar = e.inArray(t.theme, a) > -1 ? !1 : t.autoExpandScrollbar, t.scrollButtons.enable = e.inArray(t.theme, n) > -1 ? !1 : t.scrollButtons.enable, t.autoHideScrollbar = e.inArray(t.theme, i) > -1 ? !0 : t.autoHideScrollbar, t.scrollbarPosition = e.inArray(t.theme, r) > -1 ? "outside" : t.scrollbarPosition
            },
            m = function (e) {
                l[e] && (clearTimeout(l[e]), Z(l, e))
            },
            p = function (e) {
                return "yx" === e || "xy" === e || "auto" === e ? "yx" : "x" === e || "horizontal" === e ? "x" : "y"
            },
            g = function (e) {
                return "stepped" === e || "pixels" === e || "step" === e || "click" === e ? "stepped" : "stepless"
            },
            v = function () {
                var t = e(this),
                    n = t.data(a),
                    i = n.opt,
                    r = i.autoExpandScrollbar ? " " + d[1] + "_expand" : "",
                    l = ["<div id='mCSB_" + n.idx + "_scrollbar_vertical' class='mCSB_scrollTools mCSB_" + n.idx + "_scrollbar mCS-" + i.theme + " mCSB_scrollTools_vertical" + r + "'><div class='" + d[12] + "'><div id='mCSB_" + n.idx + "_dragger_vertical' class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>", "<div id='mCSB_" + n.idx + "_scrollbar_horizontal' class='mCSB_scrollTools mCSB_" + n.idx + "_scrollbar mCS-" + i.theme + " mCSB_scrollTools_horizontal" + r + "'><div class='" + d[12] + "'><div id='mCSB_" + n.idx + "_dragger_horizontal' class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>"],
                    s = "yx" === i.axis ? "mCSB_vertical_horizontal" : "x" === i.axis ? "mCSB_horizontal" : "mCSB_vertical",
                    c = "yx" === i.axis ? l[0] + l[1] : "x" === i.axis ? l[1] : l[0],
                    u = "yx" === i.axis ? "<div id='mCSB_" + n.idx + "_container_wrapper' class='mCSB_container_wrapper' />" : "",
                    f = i.autoHideScrollbar ? " " + d[6] : "",
                    h = "x" !== i.axis && "rtl" === n.langDir ? " " + d[7] : "";
                i.setWidth && t.css("width", i.setWidth), i.setHeight && t.css("height", i.setHeight), i.setLeft = "y" !== i.axis && "rtl" === n.langDir ? "989999px" : i.setLeft, t.addClass(o + " _" + a + "_" + n.idx + f + h).wrapInner("<div id='mCSB_" + n.idx + "' class='mCustomScrollBox mCS-" + i.theme + " " + s + "'><div id='mCSB_" + n.idx + "_container' class='mCSB_container' style='position:relative; top:" + i.setTop + "; left:" + i.setLeft + ";' dir=" + n.langDir + " /></div>");
                var m = e("#mCSB_" + n.idx),
                    p = e("#mCSB_" + n.idx + "_container");
                "y" === i.axis || i.advanced.autoExpandHorizontalScroll || p.css("width", x(p.children())), "outside" === i.scrollbarPosition ? ("static" === t.css("position") && t.css("position", "relative"), t.css("overflow", "visible"), m.addClass("mCSB_outside").after(c)) : (m.addClass("mCSB_inside").append(c), p.wrap(u)), w.call(this);
                var g = [e("#mCSB_" + n.idx + "_dragger_vertical"), e("#mCSB_" + n.idx + "_dragger_horizontal")];
                g[0].css("min-height", g[0].height()), g[1].css("min-width", g[1].width())
            },
            x = function (t) {
                return Math.max.apply(Math, t.map(function () {
                    return e(this).outerWidth(!0)
                }).get())
            },
            _ = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = e("#mCSB_" + o.idx + "_container");
                n.advanced.autoExpandHorizontalScroll && "y" !== n.axis && i.css({
                    position: "absolute",
                    width: "auto"
                }).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({
                    width: Math.ceil(i[0].getBoundingClientRect().right + .4) - Math.floor(i[0].getBoundingClientRect().left),
                    position: "relative"
                }).unwrap()
            },
            w = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = e(".mCSB_" + o.idx + "_scrollbar:first"),
                    r = tt(n.scrollButtons.tabindex) ? "tabindex='" + n.scrollButtons.tabindex + "'" : "",
                    l = ["<a href='#' class='" + d[13] + "' oncontextmenu='return false;' " + r + " />", "<a href='#' class='" + d[14] + "' oncontextmenu='return false;' " + r + " />", "<a href='#' class='" + d[15] + "' oncontextmenu='return false;' " + r + " />", "<a href='#' class='" + d[16] + "' oncontextmenu='return false;' " + r + " />"],
                    s = ["x" === n.axis ? l[2] : l[0], "x" === n.axis ? l[3] : l[1], l[2], l[3]];
                n.scrollButtons.enable && i.prepend(s[0]).append(s[1]).next(".mCSB_scrollTools").prepend(s[2]).append(s[3])
            },
            S = function () {
                var t = e(this),
                    o = t.data(a),
                    n = e("#mCSB_" + o.idx),
                    i = t.css("max-height") || "none",
                    r = -1 !== i.indexOf("%"),
                    l = t.css("box-sizing");
                if ("none" !== i) {
                    var s = r ? t.parent().height() * parseInt(i) / 100 : parseInt(i);
                    "border-box" === l && (s -= t.innerHeight() - t.height() + (t.outerHeight() - t.innerHeight())), n.css("max-height", Math.round(s))
                }
            },
            b = function () {
                var t = e(this),
                    o = t.data(a),
                    n = e("#mCSB_" + o.idx),
                    i = e("#mCSB_" + o.idx + "_container"),
                    r = [e("#mCSB_" + o.idx + "_dragger_vertical"), e("#mCSB_" + o.idx + "_dragger_horizontal")],
                    l = [n.height() / i.outerHeight(!1), n.width() / i.outerWidth(!1)],
                    c = [parseInt(r[0].css("min-height")), Math.round(l[0] * r[0].parent().height()), parseInt(r[1].css("min-width")), Math.round(l[1] * r[1].parent().width())],
                    d = s && c[1] < c[0] ? c[0] : c[1],
                    u = s && c[3] < c[2] ? c[2] : c[3];
                r[0].css({
                    height: d,
                    "max-height": r[0].parent().height() - 10
                }).find(".mCSB_dragger_bar").css({
                    "line-height": c[0] + "px"
                }), r[1].css({
                    width: u,
                    "max-width": r[1].parent().width() - 10
                })
            },
            C = function () {
                var t = e(this),
                    o = t.data(a),
                    n = e("#mCSB_" + o.idx),
                    i = e("#mCSB_" + o.idx + "_container"),
                    r = [e("#mCSB_" + o.idx + "_dragger_vertical"), e("#mCSB_" + o.idx + "_dragger_horizontal")],
                    l = [i.outerHeight(!1) - n.height(), i.outerWidth(!1) - n.width()],
                    s = [l[0] / (r[0].parent().height() - r[0].height()), l[1] / (r[1].parent().width() - r[1].width())];
                o.scrollRatio = {
                    y: s[0],
                    x: s[1]
                }
            },
            y = function (e, t, o) {
                var a = o ? d[0] + "_expanded" : "",
                    n = e.closest(".mCSB_scrollTools");
                "active" === t ? (e.toggleClass(d[0] + " " + a), n.toggleClass(d[1]), e[0]._draggable = e[0]._draggable ? 0 : 1) : e[0]._draggable || ("hide" === t ? (e.removeClass(d[0]), n.removeClass(d[1])) : (e.addClass(d[0]), n.addClass(d[1])))
            },
            B = function () {
                var t = e(this),
                    o = t.data(a),
                    n = e("#mCSB_" + o.idx),
                    i = e("#mCSB_" + o.idx + "_container"),
                    r = null == o.overflowed ? i.height() : i.outerHeight(!1),
                    l = null == o.overflowed ? i.width() : i.outerWidth(!1);
                return [r > n.height(), l > n.width()]
            },
            T = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = e("#mCSB_" + o.idx),
                    r = e("#mCSB_" + o.idx + "_container"),
                    l = [e("#mCSB_" + o.idx + "_dragger_vertical"), e("#mCSB_" + o.idx + "_dragger_horizontal")];
                if (V(t), ("x" !== n.axis && !o.overflowed[0] || "y" === n.axis && o.overflowed[0]) && (l[0].add(r).css("top", 0), Q(t, "_resetY")), "y" !== n.axis && !o.overflowed[1] || "x" === n.axis && o.overflowed[1]) {
                    var s = dx = 0;
                    "rtl" === o.langDir && (s = i.width() - r.outerWidth(!1), dx = Math.abs(s / o.scrollRatio.x)), r.css("left", s), l[1].css("left", dx), Q(t, "_resetX")
                }
            },
            k = function () {
                function t() {
                    r = setTimeout(function () {
                        e.event.special.mousewheel ? (clearTimeout(r), W.call(o[0])) : t()
                    }, 100)
                }
                var o = e(this),
                    n = o.data(a),
                    i = n.opt;
                if (!n.bindEvents) {
                    if (R.call(this), i.contentTouchScroll && E.call(this), D.call(this), i.mouseWheel.enable) {
                        var r;
                        t()
                    }
                    P.call(this), H.call(this), i.advanced.autoScrollOnFocus && z.call(this), i.scrollButtons.enable && U.call(this), i.keyboard.enable && q.call(this), n.bindEvents = !0
                }
            },
            M = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = a + "_" + o.idx,
                    r = ".mCSB_" + o.idx + "_scrollbar",
                    l = e("#mCSB_" + o.idx + ",#mCSB_" + o.idx + "_container,#mCSB_" + o.idx + "_container_wrapper," + r + " ." + d[12] + ",#mCSB_" + o.idx + "_dragger_vertical,#mCSB_" + o.idx + "_dragger_horizontal," + r + ">a"),
                    s = e("#mCSB_" + o.idx + "_container");
                n.advanced.releaseDraggableSelectors && l.add(e(n.advanced.releaseDraggableSelectors)), o.bindEvents && (e(document).unbind("." + i), l.each(function () {
                    e(this).unbind("." + i)
                }), clearTimeout(t[0]._focusTimeout), Z(t[0], "_focusTimeout"), clearTimeout(o.sequential.step), Z(o.sequential, "step"), clearTimeout(s[0].onCompleteTimeout), Z(s[0], "onCompleteTimeout"), o.bindEvents = !1)
            },
            O = function (t) {
                var o = e(this),
                    n = o.data(a),
                    i = n.opt,
                    r = e("#mCSB_" + n.idx + "_container_wrapper"),
                    l = r.length ? r : e("#mCSB_" + n.idx + "_container"),
                    s = [e("#mCSB_" + n.idx + "_scrollbar_vertical"), e("#mCSB_" + n.idx + "_scrollbar_horizontal")],
                    c = [s[0].find(".mCSB_dragger"), s[1].find(".mCSB_dragger")];
                "x" !== i.axis && (n.overflowed[0] && !t ? (s[0].add(c[0]).add(s[0].children("a")).css("display", "block"), l.removeClass(d[8] + " " + d[10])) : (i.alwaysShowScrollbar ? (2 !== i.alwaysShowScrollbar && c[0].css("display", "none"), l.removeClass(d[10])) : (s[0].css("display", "none"), l.addClass(d[10])), l.addClass(d[8]))), "y" !== i.axis && (n.overflowed[1] && !t ? (s[1].add(c[1]).add(s[1].children("a")).css("display", "block"), l.removeClass(d[9] + " " + d[11])) : (i.alwaysShowScrollbar ? (2 !== i.alwaysShowScrollbar && c[1].css("display", "none"), l.removeClass(d[11])) : (s[1].css("display", "none"), l.addClass(d[11])), l.addClass(d[9]))), n.overflowed[0] || n.overflowed[1] ? o.removeClass(d[5]) : o.addClass(d[5])
            },
            I = function (e) {
                var t = e.type;
                switch (t) {
                case "pointerdown":
                case "MSPointerDown":
                case "pointermove":
                case "MSPointerMove":
                case "pointerup":
                case "MSPointerUp":
                    return e.target.ownerDocument !== document ? [e.originalEvent.screenY, e.originalEvent.screenX, !1] : [e.originalEvent.pageY, e.originalEvent.pageX, !1];
                case "touchstart":
                case "touchmove":
                case "touchend":
                    var o = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0],
                        a = e.originalEvent.touches.length || e.originalEvent.changedTouches.length;
                    return e.target.ownerDocument !== document ? [o.screenY, o.screenX, a > 1] : [o.pageY, o.pageX, a > 1];
                default:
                    return [e.pageY, e.pageX, !1]
                }
            },
            R = function () {
                function t(e) {
                    var t = m.find("iframe");
                    if (t.length) {
                        var o = e ? "auto" : "none";
                        t.css("pointer-events", o)
                    }
                }

                function o(e, t, o, a) {
                    if (m[0].idleTimer = u.scrollInertia < 233 ? 250 : 0, n.attr("id") === h[1]) var i = "x",
                        r = (n[0].offsetLeft - t + a) * d.scrollRatio.x;
                    else var i = "y",
                        r = (n[0].offsetTop - e + o) * d.scrollRatio.y;
                    Q(l, r.toString(), {
                        dir: i,
                        drag: !0
                    })
                }
                var n, i, r, l = e(this),
                    d = l.data(a),
                    u = d.opt,
                    f = a + "_" + d.idx,
                    h = ["mCSB_" + d.idx + "_dragger_vertical", "mCSB_" + d.idx + "_dragger_horizontal"],
                    m = e("#mCSB_" + d.idx + "_container"),
                    p = e("#" + h[0] + ",#" + h[1]),
                    g = u.advanced.releaseDraggableSelectors ? p.add(e(u.advanced.releaseDraggableSelectors)) : p;
                p.bind("mousedown." + f + " touchstart." + f + " pointerdown." + f + " MSPointerDown." + f, function (o) {
                    if (o.stopImmediatePropagation(), o.preventDefault(), $(o)) {
                        c = !0, s && (document.onselectstart = function () {
                            return !1
                        }), t(!1), V(l), n = e(this);
                        var a = n.offset(),
                            d = I(o)[0] - a.top,
                            f = I(o)[1] - a.left,
                            h = n.height() + a.top,
                            m = n.width() + a.left;
                        h > d && d > 0 && m > f && f > 0 && (i = d, r = f), y(n, "active", u.autoExpandScrollbar)
                    }
                }).bind("touchmove." + f, function (e) {
                    e.stopImmediatePropagation(), e.preventDefault();
                    var t = n.offset(),
                        a = I(e)[0] - t.top,
                        l = I(e)[1] - t.left;
                    o(i, r, a, l)
                }), e(document).bind("mousemove." + f + " pointermove." + f + " MSPointerMove." + f, function (e) {
                    if (n) {
                        var t = n.offset(),
                            a = I(e)[0] - t.top,
                            l = I(e)[1] - t.left;
                        if (i === a) return;
                        o(i, r, a, l)
                    }
                }).add(g).bind("mouseup." + f + " touchend." + f + " pointerup." + f + " MSPointerUp." + f, function () {
                    n && (y(n, "active", u.autoExpandScrollbar), n = null), c = !1, s && (document.onselectstart = null), t(!0)
                })
            },
            E = function () {
                function o(e) {
                    if (!et(e) || c || I(e)[2]) return void(t = 0);
                    t = 1, S = 0, b = 0;
                    var o = M.offset();
                    d = I(e)[0] - o.top, u = I(e)[1] - o.left, A = [I(e)[0], I(e)[1]]
                }

                function n(e) {
                    if (et(e) && !c && !I(e)[2] && (e.stopImmediatePropagation(), !b || S)) {
                        p = J();
                        var t = k.offset(),
                            o = I(e)[0] - t.top,
                            a = I(e)[1] - t.left,
                            n = "mcsLinearOut";
                        if (R.push(o), E.push(a), A[2] = Math.abs(I(e)[0] - A[0]), A[3] = Math.abs(I(e)[1] - A[1]), y.overflowed[0]) var i = O[0].parent().height() - O[0].height(),
                            r = d - o > 0 && o - d > -(i * y.scrollRatio.y) && (2 * A[3] < A[2] || "yx" === B.axis);
                        if (y.overflowed[1]) var l = O[1].parent().width() - O[1].width(),
                            f = u - a > 0 && a - u > -(l * y.scrollRatio.x) && (2 * A[2] < A[3] || "yx" === B.axis);
                        r || f ? (e.preventDefault(), S = 1) : b = 1, _ = "yx" === B.axis ? [d - o, u - a] : "x" === B.axis ? [null, u - a] : [d - o, null], M[0].idleTimer = 250, y.overflowed[0] && s(_[0], D, n, "y", "all", !0), y.overflowed[1] && s(_[1], D, n, "x", W, !0)
                    }
                }

                function i(e) {
                    if (!et(e) || c || I(e)[2]) return void(t = 0);
                    t = 1, e.stopImmediatePropagation(), V(C), m = J();
                    var o = k.offset();
                    f = I(e)[0] - o.top, h = I(e)[1] - o.left, R = [], E = []
                }

                function r(e) {
                    if (et(e) && !c && !I(e)[2]) {
                        e.stopImmediatePropagation(), S = 0, b = 0, g = J();
                        var t = k.offset(),
                            o = I(e)[0] - t.top,
                            a = I(e)[1] - t.left;
                        if (!(g - p > 30)) {
                            x = 1e3 / (g - m);
                            var n = "mcsEaseOut",
                                i = 2.5 > x,
                                r = i ? [R[R.length - 2], E[E.length - 2]] : [0, 0];
                            v = i ? [o - r[0], a - r[1]] : [o - f, a - h];
                            var d = [Math.abs(v[0]), Math.abs(v[1])];
                            x = i ? [Math.abs(v[0] / 4), Math.abs(v[1] / 4)] : [x, x];
                            var u = [Math.abs(M[0].offsetTop) - v[0] * l(d[0] / x[0], x[0]), Math.abs(M[0].offsetLeft) - v[1] * l(d[1] / x[1], x[1])];
                            _ = "yx" === B.axis ? [u[0], u[1]] : "x" === B.axis ? [null, u[1]] : [u[0], null], w = [4 * d[0] + B.scrollInertia, 4 * d[1] + B.scrollInertia];
                            var C = parseInt(B.contentTouchScroll) || 0;
                            _[0] = d[0] > C ? _[0] : 0, _[1] = d[1] > C ? _[1] : 0, y.overflowed[0] && s(_[0], w[0], n, "y", W, !1), y.overflowed[1] && s(_[1], w[1], n, "x", W, !1)
                        }
                    }
                }

                function l(e, t) {
                    var o = [1.5 * t, 2 * t, t / 1.5, t / 2];
                    return e > 90 ? t > 4 ? o[0] : o[3] : e > 60 ? t > 3 ? o[3] : o[2] : e > 30 ? t > 8 ? o[1] : t > 6 ? o[0] : t > 4 ? t : o[2] : t > 8 ? t : o[3]
                }

                function s(e, t, o, a, n, i) {
                    e && Q(C, e.toString(), {
                        dur: t,
                        scrollEasing: o,
                        dir: a,
                        overwrite: n,
                        drag: i
                    })
                }
                var d, u, f, h, m, p, g, v, x, _, w, S, b, C = e(this),
                    y = C.data(a),
                    B = y.opt,
                    T = a + "_" + y.idx,
                    k = e("#mCSB_" + y.idx),
                    M = e("#mCSB_" + y.idx + "_container"),
                    O = [e("#mCSB_" + y.idx + "_dragger_vertical"), e("#mCSB_" + y.idx + "_dragger_horizontal")],
                    R = [],
                    E = [],
                    D = 0,
                    W = "yx" === B.axis ? "none" : "all",
                    A = [],
                    P = M.find("iframe"),
                    z = ["touchstart." + T + " pointerdown." + T + " MSPointerDown." + T, "touchmove." + T + " pointermove." + T + " MSPointerMove." + T, "touchend." + T + " pointerup." + T + " MSPointerUp." + T];
                M.bind(z[0], function (e) {
                    o(e)
                }).bind(z[1], function (e) {
                    n(e)
                }), k.bind(z[0], function (e) {
                    i(e)
                }).bind(z[2], function (e) {
                    r(e)
                }), P.length && P.each(function () {
                    e(this).load(function () {
                        L(this) && e(this.contentDocument || this.contentWindow.document).bind(z[0], function (e) {
                            o(e), i(e)
                        }).bind(z[1], function (e) {
                            n(e)
                        }).bind(z[2], function (e) {
                            r(e)
                        })
                    })
                })
            },
            D = function () {
                function o() {
                    return window.getSelection ? window.getSelection().toString() : document.selection && "Control" != document.selection.type ? document.selection.createRange().text : 0
                }

                function n(e, t, o) {
                    d.type = o && i ? "stepped" : "stepless", d.scrollAmount = 10, F(r, e, t, "mcsLinearOut", o ? 60 : null)
                }
                var i, r = e(this),
                    l = r.data(a),
                    s = l.opt,
                    d = l.sequential,
                    u = a + "_" + l.idx,
                    f = e("#mCSB_" + l.idx + "_container"),
                    h = f.parent();
                f.bind("mousedown." + u, function () {
                    t || i || (i = 1, c = !0)
                }).add(document).bind("mousemove." + u, function (e) {
                    if (!t && i && o()) {
                        var a = f.offset(),
                            r = I(e)[0] - a.top + f[0].offsetTop,
                            c = I(e)[1] - a.left + f[0].offsetLeft;
                        r > 0 && r < h.height() && c > 0 && c < h.width() ? d.step && n("off", null, "stepped") : ("x" !== s.axis && l.overflowed[0] && (0 > r ? n("on", 38) : r > h.height() && n("on", 40)), "y" !== s.axis && l.overflowed[1] && (0 > c ? n("on", 37) : c > h.width() && n("on", 39)))
                    }
                }).bind("mouseup." + u, function () {
                    t || (i && (i = 0, n("off", null)), c = !1)
                })
            },
            W = function () {
                function t(t, a) {
                    if (V(o), !A(o, t.target)) {
                        var r = "auto" !== i.mouseWheel.deltaFactor ? parseInt(i.mouseWheel.deltaFactor) : s && t.deltaFactor < 100 ? 100 : t.deltaFactor || 100;
                        if ("x" === i.axis || "x" === i.mouseWheel.axis) var d = "x",
                            u = [Math.round(r * n.scrollRatio.x), parseInt(i.mouseWheel.scrollAmount)],
                            f = "auto" !== i.mouseWheel.scrollAmount ? u[1] : u[0] >= l.width() ? .9 * l.width() : u[0],
                            h = Math.abs(e("#mCSB_" + n.idx + "_container")[0].offsetLeft),
                            m = c[1][0].offsetLeft,
                            p = c[1].parent().width() - c[1].width(),
                            g = t.deltaX || t.deltaY || a;
                        else var d = "y",
                            u = [Math.round(r * n.scrollRatio.y), parseInt(i.mouseWheel.scrollAmount)],
                            f = "auto" !== i.mouseWheel.scrollAmount ? u[1] : u[0] >= l.height() ? .9 * l.height() : u[0],
                            h = Math.abs(e("#mCSB_" + n.idx + "_container")[0].offsetTop),
                            m = c[0][0].offsetTop,
                            p = c[0].parent().height() - c[0].height(),
                            g = t.deltaY || a;
                        "y" === d && !n.overflowed[0] || "x" === d && !n.overflowed[1] || (i.mouseWheel.invert && (g = -g), i.mouseWheel.normalizeDelta && (g = 0 > g ? -1 : 1), (g > 0 && 0 !== m || 0 > g && m !== p || i.mouseWheel.preventDefault) && (t.stopImmediatePropagation(), t.preventDefault()), Q(o, (h - g * f).toString(), {
                            dir: d
                        }))
                    }
                }
                var o = e(this),
                    n = o.data(a),
                    i = n.opt,
                    r = a + "_" + n.idx,
                    l = e("#mCSB_" + n.idx),
                    c = [e("#mCSB_" + n.idx + "_dragger_vertical"), e("#mCSB_" + n.idx + "_dragger_horizontal")],
                    d = e("#mCSB_" + n.idx + "_container").find("iframe");
                n && (d.length && d.each(function () {
                    e(this).load(function () {
                        L(this) && e(this.contentDocument || this.contentWindow.document).bind("mousewheel." + r, function (e, o) {
                            t(e, o)
                        })
                    })
                }), l.bind("mousewheel." + r, function (e, o) {
                    t(e, o)
                }))
            },
            L = function (e) {
                var t = null;
                try {
                    var o = e.contentDocument || e.contentWindow.document;
                    t = o.body.innerHTML
                } catch (a) {}
                return null !== t
            },
            A = function (t, o) {
                var n = o.nodeName.toLowerCase(),
                    i = t.data(a).opt.mouseWheel.disableOver,
                    r = ["select", "textarea"];
                return e.inArray(n, i) > -1 && !(e.inArray(n, r) > -1 && !e(o).is(":focus"))
            },
            P = function () {
                var t = e(this),
                    o = t.data(a),
                    n = a + "_" + o.idx,
                    i = e("#mCSB_" + o.idx + "_container"),
                    r = i.parent(),
                    l = e(".mCSB_" + o.idx + "_scrollbar ." + d[12]);
                l.bind("touchstart." + n + " pointerdown." + n + " MSPointerDown." + n, function () {
                    c = !0
                }).bind("touchend." + n + " pointerup." + n + " MSPointerUp." + n, function () {
                    c = !1
                }).bind("click." + n, function (a) {
                    if (e(a.target).hasClass(d[12]) || e(a.target).hasClass("mCSB_draggerRail")) {
                        V(t);
                        var n = e(this),
                            l = n.find(".mCSB_dragger");
                        if (n.parent(".mCSB_scrollTools_horizontal").length > 0) {
                            if (!o.overflowed[1]) return;
                            var s = "x",
                                c = a.pageX > l.offset().left ? -1 : 1,
                                u = Math.abs(i[0].offsetLeft) - .9 * c * r.width()
                        } else {
                            if (!o.overflowed[0]) return;
                            var s = "y",
                                c = a.pageY > l.offset().top ? -1 : 1,
                                u = Math.abs(i[0].offsetTop) - .9 * c * r.height()
                        }
                        Q(t, u.toString(), {
                            dir: s,
                            scrollEasing: "mcsEaseInOut"
                        })
                    }
                })
            },
            z = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = a + "_" + o.idx,
                    r = e("#mCSB_" + o.idx + "_container"),
                    l = r.parent();
                r.bind("focusin." + i, function () {
                    var o = e(document.activeElement),
                        a = r.find(".mCustomScrollBox").length,
                        i = 0;
                    o.is(n.advanced.autoScrollOnFocus) && (V(t), clearTimeout(t[0]._focusTimeout), t[0]._focusTimer = a ? (i + 17) * a : 0, t[0]._focusTimeout = setTimeout(function () {
                        var e = [ot(o)[0], ot(o)[1]],
                            a = [r[0].offsetTop, r[0].offsetLeft],
                            s = [a[0] + e[0] >= 0 && a[0] + e[0] < l.height() - o.outerHeight(!1), a[1] + e[1] >= 0 && a[0] + e[1] < l.width() - o.outerWidth(!1)],
                            c = "yx" !== n.axis || s[0] || s[1] ? "all" : "none";
                        "x" === n.axis || s[0] || Q(t, e[0].toString(), {
                            dir: "y",
                            scrollEasing: "mcsEaseInOut",
                            overwrite: c,
                            dur: i
                        }), "y" === n.axis || s[1] || Q(t, e[1].toString(), {
                            dir: "x",
                            scrollEasing: "mcsEaseInOut",
                            overwrite: c,
                            dur: i
                        })
                    }, t[0]._focusTimer))
                })
            },
            H = function () {
                var t = e(this),
                    o = t.data(a),
                    n = a + "_" + o.idx,
                    i = e("#mCSB_" + o.idx + "_container").parent();
                i.bind("scroll." + n, function () {
                    (0 !== i.scrollTop() || 0 !== i.scrollLeft()) && e(".mCSB_" + o.idx + "_scrollbar").css("visibility", "hidden")
                })
            },
            U = function () {
                var t = e(this),
                    o = t.data(a),
                    n = o.opt,
                    i = o.sequential,
                    r = a + "_" + o.idx,
                    l = ".mCSB_" + o.idx + "_scrollbar",
                    s = e(l + ">a");
                s.bind("mousedown." + r + " touchstart." + r + " pointerdown." + r + " MSPointerDown." + r + " mouseup." + r + " touchend." + r + " pointerup." + r + " MSPointerUp." + r + " mouseout." + r + " pointerout." + r + " MSPointerOut." + r + " click." + r, function (a) {
                    function r(e, o) {
                        i.scrollAmount = n.snapAmount || n.scrollButtons.scrollAmount, F(t, e, o)
                    }
                    if (a.preventDefault(), $(a)) {
                        var l = e(this).attr("class");
                        switch (i.type = n.scrollButtons.scrollType, a.type) {
                        case "mousedown":
                        case "touchstart":
                        case "pointerdown":
                        case "MSPointerDown":
                            if ("stepped" === i.type) return;
                            c = !0, o.tweenRunning = !1, r("on", l);
                            break;
                        case "mouseup":
                        case "touchend":
                        case "pointerup":
                        case "MSPointerUp":
                        case "mouseout":
                        case "pointerout":
                        case "MSPointerOut":
                            if ("stepped" === i.type) return;
                            c = !1, i.dir && r("off", l);
                            break;
                        case "click":
                            if ("stepped" !== i.type || o.tweenRunning) return;
                            r("on", l)
                        }
                    }
                })
            },
            q = function () {
                function t(t) {
                    function a(e, t) {
                        r.type = i.keyboard.scrollType, r.scrollAmount = i.snapAmount || i.keyboard.scrollAmount, "stepped" === r.type && n.tweenRunning || F(o, e, t)
                    }
                    switch (t.type) {
                    case "blur":
                        n.tweenRunning && r.dir && a("off", null);
                        break;
                    case "keydown":
                    case "keyup":
                        var l = t.keyCode ? t.keyCode : t.which,
                            s = "on";
                        if ("x" !== i.axis && (38 === l || 40 === l) || "y" !== i.axis && (37 === l || 39 === l)) {
                            if ((38 === l || 40 === l) && !n.overflowed[0] || (37 === l || 39 === l) && !n.overflowed[1]) return;
                            "keyup" === t.type && (s = "off"), e(document.activeElement).is(u) || (t.preventDefault(), t.stopImmediatePropagation(), a(s, l))
                        } else if (33 === l || 34 === l) {
                            if ((n.overflowed[0] || n.overflowed[1]) && (t.preventDefault(), t.stopImmediatePropagation()), "keyup" === t.type) {
                                V(o);
                                var f = 34 === l ? -1 : 1;
                                if ("x" === i.axis || "yx" === i.axis && n.overflowed[1] && !n.overflowed[0]) var h = "x",
                                    m = Math.abs(c[0].offsetLeft) - .9 * f * d.width();
                                else var h = "y",
                                    m = Math.abs(c[0].offsetTop) - .9 * f * d.height();
                                Q(o, m.toString(), {
                                    dir: h,
                                    scrollEasing: "mcsEaseInOut"
                                })
                            }
                        } else if ((35 === l || 36 === l) && !e(document.activeElement).is(u) && ((n.overflowed[0] || n.overflowed[1]) && (t.preventDefault(), t.stopImmediatePropagation()), "keyup" === t.type)) {
                            if ("x" === i.axis || "yx" === i.axis && n.overflowed[1] && !n.overflowed[0]) var h = "x",
                                m = 35 === l ? Math.abs(d.width() - c.outerWidth(!1)) : 0;
                            else var h = "y",
                                m = 35 === l ? Math.abs(d.height() - c.outerHeight(!1)) : 0;
                            Q(o, m.toString(), {
                                dir: h,
                                scrollEasing: "mcsEaseInOut"
                            })
                        }
                    }
                }
                var o = e(this),
                    n = o.data(a),
                    i = n.opt,
                    r = n.sequential,
                    l = a + "_" + n.idx,
                    s = e("#mCSB_" + n.idx),
                    c = e("#mCSB_" + n.idx + "_container"),
                    d = c.parent(),
                    u = "input,textarea,select,datalist,keygen,[contenteditable='true']",
                    f = c.find("iframe"),
                    h = ["blur." + l + " keydown." + l + " keyup." + l];
                f.length && f.each(function () {
                    e(this).load(function () {
                        L(this) && e(this.contentDocument || this.contentWindow.document).bind(h[0], function (e) {
                            t(e)
                        })
                    })
                }), s.attr("tabindex", "0").bind(h[0], function (e) {
                    t(e)
                })
            },
            F = function (t, o, n, i, r) {
                function l(e) {
                    var o = "stepped" !== f.type,
                        a = r ? r : e ? o ? p / 1.5 : g : 1e3 / 60,
                        n = e ? o ? 7.5 : 40 : 2.5,
                        s = [Math.abs(h[0].offsetTop), Math.abs(h[0].offsetLeft)],
                        d = [c.scrollRatio.y > 10 ? 10 : c.scrollRatio.y, c.scrollRatio.x > 10 ? 10 : c.scrollRatio.x],
                        u = "x" === f.dir[0] ? s[1] + f.dir[1] * d[1] * n : s[0] + f.dir[1] * d[0] * n,
                        m = "x" === f.dir[0] ? s[1] + f.dir[1] * parseInt(f.scrollAmount) : s[0] + f.dir[1] * parseInt(f.scrollAmount),
                        v = "auto" !== f.scrollAmount ? m : u,
                        x = i ? i : e ? o ? "mcsLinearOut" : "mcsEaseInOut" : "mcsLinear",
                        _ = e ? !0 : !1;
                    return e && 17 > a && (v = "x" === f.dir[0] ? s[1] : s[0]), Q(t, v.toString(), {
                        dir: f.dir[0],
                        scrollEasing: x,
                        dur: a,
                        onComplete: _
                    }), e ? void(f.dir = !1) : (clearTimeout(f.step), void(f.step = setTimeout(function () {
                        l()
                    }, a)))
                }

                function s() {
                    clearTimeout(f.step), Z(f, "step"), V(t)
                }
                var c = t.data(a),
                    u = c.opt,
                    f = c.sequential,
                    h = e("#mCSB_" + c.idx + "_container"),
                    m = "stepped" === f.type ? !0 : !1,
                    p = u.scrollInertia < 26 ? 26 : u.scrollInertia,
                    g = u.scrollInertia < 1 ? 17 : u.scrollInertia;
                switch (o) {
                case "on":
                    if (f.dir = [n === d[16] || n === d[15] || 39 === n || 37 === n ? "x" : "y", n === d[13] || n === d[15] || 38 === n || 37 === n ? -1 : 1], V(t), tt(n) && "stepped" === f.type) return;
                    l(m);
                    break;
                case "off":
                    s(), (m || c.tweenRunning && f.dir) && l(!0)
                }
            },
            Y = function (t) {
                var o = e(this).data(a).opt,
                    n = [];
                return "function" == typeof t && (t = t()), t instanceof Array ? n = t.length > 1 ? [t[0], t[1]] : "x" === o.axis ? [null, t[0]] : [t[0], null] : (n[0] = t.y ? t.y : t.x || "x" === o.axis ? null : t, n[1] = t.x ? t.x : t.y || "y" === o.axis ? null : t), "function" == typeof n[0] && (n[0] = n[0]()), "function" == typeof n[1] && (n[1] = n[1]()), n
            },
            j = function (t, o) {
                if (null != t && "undefined" != typeof t) {
                    var n = e(this),
                        i = n.data(a),
                        r = i.opt,
                        l = e("#mCSB_" + i.idx + "_container"),
                        s = l.parent(),
                        c = typeof t;
                    o || (o = "x" === r.axis ? "x" : "y");
                    var d = "x" === o ? l.outerWidth(!1) : l.outerHeight(!1),
                        f = "x" === o ? l[0].offsetLeft : l[0].offsetTop,
                        h = "x" === o ? "left" : "top";
                    switch (c) {
                    case "function":
                        return t();
                    case "object":
                        var m = t.jquery ? t : e(t);
                        if (!m.length) return;
                        return "x" === o ? ot(m)[1] : ot(m)[0];
                    case "string":
                    case "number":
                        if (tt(t)) return Math.abs(t);
                        if (-1 !== t.indexOf("%")) return Math.abs(d * parseInt(t) / 100);
                        if (-1 !== t.indexOf("-=")) return Math.abs(f - parseInt(t.split("-=")[1]));
                        if (-1 !== t.indexOf("+=")) {
                            var p = f + parseInt(t.split("+=")[1]);
                            return p >= 0 ? 0 : Math.abs(p)
                        }
                        if (-1 !== t.indexOf("px") && tt(t.split("px")[0])) return Math.abs(t.split("px")[0]);
                        if ("top" === t || "left" === t) return 0;
                        if ("bottom" === t) return Math.abs(s.height() - l.outerHeight(!1));
                        if ("right" === t) return Math.abs(s.width() - l.outerWidth(!1));
                        if ("first" === t || "last" === t) {
                            var m = l.find(":" + t);
                            return "x" === o ? ot(m)[1] : ot(m)[0]
                        }
                        return e(t).length ? "x" === o ? ot(e(t))[1] : ot(e(t))[0] : (l.css(h, t), void u.update.call(null, n[0]))
                    }
                }
            },
            X = function (t) {
                function o() {
                    clearTimeout(h[0].autoUpdate), h[0].autoUpdate = setTimeout(function () {
                        return f.advanced.updateOnSelectorChange && (m = r(), m !== w) ? (l(3), void(w = m)) : (f.advanced.updateOnContentResize && (p = [h.outerHeight(!1), h.outerWidth(!1), v.height(), v.width(), _()[0], _()[1]], (p[0] !== S[0] || p[1] !== S[1] || p[2] !== S[2] || p[3] !== S[3] || p[4] !== S[4] || p[5] !== S[5]) && (l(p[0] !== S[0] || p[1] !== S[1]), S = p)), f.advanced.updateOnImageLoad && (g = n(), g !== b && (h.find("img").each(function () {
                            i(this)
                        }), b = g)), void((f.advanced.updateOnSelectorChange || f.advanced.updateOnContentResize || f.advanced.updateOnImageLoad) && o()))
                    }, 60)
                }

                function n() {
                    var e = 0;
                    return f.advanced.updateOnImageLoad && (e = h.find("img").length), e
                }

                function i(t) {
                    function o(e, t) {
                        return function () {
                            return t.apply(e, arguments)
                        }
                    }

                    function a() {
                        this.onload = null, e(t).addClass(d[2]), l(2)
                    }
                    if (e(t).hasClass(d[2])) return void l();
                    var n = new Image;
                    n.onload = o(n, a), n.src = t.src
                }

                function r() {
                    f.advanced.updateOnSelectorChange === !0 && (f.advanced.updateOnSelectorChange = "*");
                    var t = 0,
                        o = h.find(f.advanced.updateOnSelectorChange);
                    return f.advanced.updateOnSelectorChange && o.length > 0 && o.each(function () {
                        t += e(this).height() + e(this).width()
                    }), t
                }

                function l(e) {
                    clearTimeout(h[0].autoUpdate), u.update.call(null, s[0], e)
                }
                var s = e(this),
                    c = s.data(a),
                    f = c.opt,
                    h = e("#mCSB_" + c.idx + "_container");
                if (t) return clearTimeout(h[0].autoUpdate), void Z(h[0], "autoUpdate");
                var m, p, g, v = h.parent(),
                    x = [e("#mCSB_" + c.idx + "_scrollbar_vertical"), e("#mCSB_" + c.idx + "_scrollbar_horizontal")],
                    _ = function () {
                        return [x[0].is(":visible") ? x[0].outerHeight(!0) : 0, x[1].is(":visible") ? x[1].outerWidth(!0) : 0]
                    },
                    w = r(),
                    S = [h.outerHeight(!1), h.outerWidth(!1), v.height(), v.width(), _()[0], _()[1]],
                    b = n();
                o()
            },
            N = function (e, t, o) {
                return Math.round(e / t) * t - o
            },
            V = function (t) {
                var o = t.data(a),
                    n = e("#mCSB_" + o.idx + "_container,#mCSB_" + o.idx + "_container_wrapper,#mCSB_" + o.idx + "_dragger_vertical,#mCSB_" + o.idx + "_dragger_horizontal");
                n.each(function () {
                    K.call(this)
                })
            },
            Q = function (t, o, n) {
                function i(e) {
                    return s && c.callbacks[e] && "function" == typeof c.callbacks[e]
                }

                function r() {
                    return [c.callbacks.alwaysTriggerOffsets || _ >= w[0] + b, c.callbacks.alwaysTriggerOffsets || -C >= _]
                }

                function l() {
                    var e = [h[0].offsetTop, h[0].offsetLeft],
                        o = [v[0].offsetTop, v[0].offsetLeft],
                        a = [h.outerHeight(!1), h.outerWidth(!1)],
                        i = [f.height(), f.width()];
                    t[0].mcs = {
                        content: h,
                        top: e[0],
                        left: e[1],
                        draggerTop: o[0],
                        draggerLeft: o[1],
                        topPct: Math.round(100 * Math.abs(e[0]) / (Math.abs(a[0]) - i[0])),
                        leftPct: Math.round(100 * Math.abs(e[1]) / (Math.abs(a[1]) - i[1])),
                        direction: n.dir
                    }
                }
                var s = t.data(a),
                    c = s.opt,
                    d = {
                        trigger: "internal",
                        dir: "y",
                        scrollEasing: "mcsEaseOut",
                        drag: !1,
                        dur: c.scrollInertia,
                        overwrite: "all",
                        callbacks: !0,
                        onStart: !0,
                        onUpdate: !0,
                        onComplete: !0
                    },
                    n = e.extend(d, n),
                    u = [n.dur, n.drag ? 0 : n.dur],
                    f = e("#mCSB_" + s.idx),
                    h = e("#mCSB_" + s.idx + "_container"),
                    m = h.parent(),
                    p = c.callbacks.onTotalScrollOffset ? Y.call(t, c.callbacks.onTotalScrollOffset) : [0, 0],
                    g = c.callbacks.onTotalScrollBackOffset ? Y.call(t, c.callbacks.onTotalScrollBackOffset) : [0, 0];
                if (s.trigger = n.trigger, (0 !== m.scrollTop() || 0 !== m.scrollLeft()) && (e(".mCSB_" + s.idx + "_scrollbar").css("visibility", "visible"), m.scrollTop(0).scrollLeft(0)), "_resetY" !== o || s.contentReset.y || (i("onOverflowYNone") && c.callbacks.onOverflowYNone.call(t[0]), s.contentReset.y = 1), "_resetX" !== o || s.contentReset.x || (i("onOverflowXNone") && c.callbacks.onOverflowXNone.call(t[0]), s.contentReset.x = 1), "_resetY" !== o && "_resetX" !== o) {
                    switch (!s.contentReset.y && t[0].mcs || !s.overflowed[0] || (i("onOverflowY") && c.callbacks.onOverflowY.call(t[0]), s.contentReset.x = null), !s.contentReset.x && t[0].mcs || !s.overflowed[1] || (i("onOverflowX") && c.callbacks.onOverflowX.call(t[0]), s.contentReset.x = null), c.snapAmount && (o = N(o, c.snapAmount, c.snapOffset)), n.dir) {
                    case "x":
                        var v = e("#mCSB_" + s.idx + "_dragger_horizontal"),
                            x = "left",
                            _ = h[0].offsetLeft,
                            w = [f.width() - h.outerWidth(!1), v.parent().width() - v.width()],
                            S = [o, 0 === o ? 0 : o / s.scrollRatio.x],
                            b = p[1],
                            C = g[1],
                            B = b > 0 ? b / s.scrollRatio.x : 0,
                            T = C > 0 ? C / s.scrollRatio.x : 0;
                        break;
                    case "y":
                        var v = e("#mCSB_" + s.idx + "_dragger_vertical"),
                            x = "top",
                            _ = h[0].offsetTop,
                            w = [f.height() - h.outerHeight(!1), v.parent().height() - v.height()],
                            S = [o, 0 === o ? 0 : o / s.scrollRatio.y],
                            b = p[0],
                            C = g[0],
                            B = b > 0 ? b / s.scrollRatio.y : 0,
                            T = C > 0 ? C / s.scrollRatio.y : 0
                    }
                    S[1] < 0 || 0 === S[0] && 0 === S[1] ? S = [0, 0] : S[1] >= w[1] ? S = [w[0], w[1]] : S[0] = -S[0], t[0].mcs || (l(), i("onInit") && c.callbacks.onInit.call(t[0])), clearTimeout(h[0].onCompleteTimeout), (s.tweenRunning || !(0 === _ && S[0] >= 0 || _ === w[0] && S[0] <= w[0])) && (G(v[0], x, Math.round(S[1]), u[1], n.scrollEasing), G(h[0], x, Math.round(S[0]), u[0], n.scrollEasing, n.overwrite, {
                        onStart: function () {
                            n.callbacks && n.onStart && !s.tweenRunning && (i("onScrollStart") && (l(), c.callbacks.onScrollStart.call(t[0])), s.tweenRunning = !0, y(v), s.cbOffsets = r())
                        },
                        onUpdate: function () {
                            n.callbacks && n.onUpdate && i("whileScrolling") && (l(), c.callbacks.whileScrolling.call(t[0]))
                        },
                        onComplete: function () {
                            if (n.callbacks && n.onComplete) {
                                "yx" === c.axis && clearTimeout(h[0].onCompleteTimeout);
                                var e = h[0].idleTimer || 0;
                                h[0].onCompleteTimeout = setTimeout(function () {
                                    i("onScroll") && (l(), c.callbacks.onScroll.call(t[0])), i("onTotalScroll") && S[1] >= w[1] - B && s.cbOffsets[0] && (l(), c.callbacks.onTotalScroll.call(t[0])), i("onTotalScrollBack") && S[1] <= T && s.cbOffsets[1] && (l(), c.callbacks.onTotalScrollBack.call(t[0])), s.tweenRunning = !1, h[0].idleTimer = 0, y(v, "hide")
                                }, e)
                            }
                        }
                    }))
                }
            },
            G = function (e, t, o, a, n, i, r) {
                function l() {
                    S.stop || (x || m.call(), x = J() - v, s(), x >= S.time && (S.time = x > S.time ? x + f - (x - S.time) : x + f - 1, S.time < x + 1 && (S.time = x + 1)), S.time < a ? S.id = h(l) : g.call())
                }

                function s() {
                    a > 0 ? (S.currVal = u(S.time, _, b, a, n), w[t] = Math.round(S.currVal) + "px") : w[t] = o + "px", p.call()
                }

                function c() {
                    f = 1e3 / 60, S.time = x + f, h = window.requestAnimationFrame ? window.requestAnimationFrame : function (e) {
                        return s(), setTimeout(e, .01)
                    }, S.id = h(l)
                }

                function d() {
                    null != S.id && (window.requestAnimationFrame ? window.cancelAnimationFrame(S.id) : clearTimeout(S.id), S.id = null)
                }

                function u(e, t, o, a, n) {
                    switch (n) {
                    case "linear":
                    case "mcsLinear":
                        return o * e / a + t;
                    case "mcsLinearOut":
                        return e /= a, e--, o * Math.sqrt(1 - e * e) + t;
                    case "easeInOutSmooth":
                        return e /= a / 2, 1 > e ? o / 2 * e * e + t : (e--, -o / 2 * (e * (e - 2) - 1) + t);
                    case "easeInOutStrong":
                        return e /= a / 2, 1 > e ? o / 2 * Math.pow(2, 10 * (e - 1)) + t : (e--, o / 2 * (-Math.pow(2, -10 * e) + 2) + t);
                    case "easeInOut":
                    case "mcsEaseInOut":
                        return e /= a / 2, 1 > e ? o / 2 * e * e * e + t : (e -= 2, o / 2 * (e * e * e + 2) + t);
                    case "easeOutSmooth":
                        return e /= a, e--, -o * (e * e * e * e - 1) + t;
                    case "easeOutStrong":
                        return o * (-Math.pow(2, -10 * e / a) + 1) + t;
                    case "easeOut":
                    case "mcsEaseOut":
                    default:
                        var i = (e /= a) * e,
                            r = i * e;
                        return t + o * (.499999999999997 * r * i + -2.5 * i * i + 5.5 * r + -6.5 * i + 4 * e)
                    }
                }
                e._mTween || (e._mTween = {
                    top: {},
                    left: {}
                });
                var f, h, r = r || {},
                    m = r.onStart || function () {},
                    p = r.onUpdate || function () {},
                    g = r.onComplete || function () {},
                    v = J(),
                    x = 0,
                    _ = e.offsetTop,
                    w = e.style,
                    S = e._mTween[t];
                "left" === t && (_ = e.offsetLeft);
                var b = o - _;
                S.stop = 0, "none" !== i && d(), c()
            },
            J = function () {
                return window.performance && window.performance.now ? window.performance.now() : window.performance && window.performance.webkitNow ? window.performance.webkitNow() : Date.now ? Date.now() : (new Date).getTime()
            },
            K = function () {
                var e = this;
                e._mTween || (e._mTween = {
                    top: {},
                    left: {}
                });
                for (var t = ["top", "left"], o = 0; o < t.length; o++) {
                    var a = t[o];
                    e._mTween[a].id && (window.requestAnimationFrame ? window.cancelAnimationFrame(e._mTween[a].id) : clearTimeout(e._mTween[a].id), e._mTween[a].id = null, e._mTween[a].stop = 1)
                }
            },
            Z = function (e, t) {
                try {
                    delete e[t]
                } catch (o) {
                    e[t] = null
                }
            },
            $ = function (e) {
                return !(e.which && 1 !== e.which)
            },
            et = function (e) {
                var t = e.originalEvent.pointerType;
                return !(t && "touch" !== t && 2 !== t)
            },
            tt = function (e) {
                return !isNaN(parseFloat(e)) && isFinite(e)
            },
            ot = function (e) {
                var t = e.parents(".mCSB_container");
                return [e.offset().top - t.offset().top, e.offset().left - t.offset().left]
            };
        e.fn[o] = function (t) {
            return u[t] ? u[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void e.error("Method " + t + " does not exist") : u.init.apply(this, arguments)
        }, e[o] = function (t) {
            return u[t] ? u[t].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof t && t ? void e.error("Method " + t + " does not exist") : u.init.apply(this, arguments)
        }, e[o].defaults = i, window[o] = !0, e(window).load(function () {
            e(n)[o](), e.extend(e.expr[":"], {
                mcsInView: e.expr[":"].mcsInView || function (t) {
                    var o, a, n = e(t),
                        i = n.parents(".mCSB_container");
                    if (i.length) return o = i.parent(), a = [i[0].offsetTop, i[0].offsetLeft], a[0] + ot(n)[0] >= 0 && a[0] + ot(n)[0] < o.height() - n.outerHeight(!1) && a[1] + ot(n)[1] >= 0 && a[1] + ot(n)[1] < o.width() - n.outerWidth(!1)
                },
                mcsOverflow: e.expr[":"].mcsOverflow || function (t) {
                    var o = e(t).data(a);
                    if (o) return o.overflowed[0] || o.overflowed[1]
                }
            })
        })
    })
});

// jquery.pajinate.js - version 0.4
// A jQuery plugin for paginating through any number of DOM elements
// 
// Copyright (c) 2010, Wes Nolte (http://wesnolte.com)
// Licensed under the MIT License (MIT-LICENSE.txt)
// http://www.opensource.org/licenses/mit-license.php
// Created: 2010-04-16 | Updated: 2010-04-26
(function (n) {
    n.fn.pajinate = function (t) {
        function d(i) {
            new_page = parseInt(u.data(o)) - 1, n(i).siblings(".active_page").prev(".page_link").length == !0 ? (p(i, new_page), f(new_page)) : t.wrap_around && f(c - 1)
        }

        function g(i) {
            new_page = parseInt(u.data(o)) + 1, n(i).siblings(".active_page").next(".page_link").length == !0 ? (y(i, new_page), f(new_page)) : t.wrap_around && f(0)
        }

        function f(n) {
            var i, f;
            n = parseInt(n, 10), i = parseInt(u.data(v)), start_from = n * i, end_on = start_from + i, f = e.hide().slice(start_from, end_on), f.show(), r.find(t.nav_panel_id).children(".page_link[longdesc=" + n + "]").addClass("active_page " + a).siblings(".active_page").removeClass("active_page " + a), u.data(o, n);
            var s = parseInt(u.data(o) + 1),
                h = l.children().size(),
                c = Math.ceil(h / t.items_per_page);
            r.find(t.nav_info_id).html(t.nav_label_info.replace("{0}", start_from + 1).replace("{1}", start_from + f.length).replace("{2}", e.length).replace("{3}", s).replace("{4}", c)), w(), b(), typeof t.onPageDisplayed != "undefined" && t.onPageDisplayed.call(this, n + 1)
        }

        function y(r, u) {
            var f = u,
                e = n(r).siblings(".active_page");
            e.siblings(".page_link[longdesc=" + f + "]").css("display") == "none" && i.each(function () {
                n(this).children(".page_link").hide().slice(parseInt(f - t.num_page_links_to_display + 1), f + 1).show()
            })
        }

        function p(r, u) {
            var f = u,
                e = n(r).siblings(".active_page");
            e.siblings(".page_link[longdesc=" + f + "]").css("display") == "none" && i.each(function () {
                n(this).children(".page_link").hide().slice(f, f + parseInt(t.num_page_links_to_display)).show()
            })
        }

        function w() {
            i.children(".page_link:visible").hasClass("last") ? i.children(".more").hide() : i.children(".more").show(), i.children(".page_link:visible").hasClass("first") ? i.children(".less").hide() : i.children(".less").show()
        }

        function b() {
            i.children(".last").hasClass("active_page") ? i.children(".next_link").add(".last_link").addClass("no_more " + h) : i.children(".next_link").add(".last_link").removeClass("no_more " + h), i.children(".first").hasClass("active_page") ? i.children(".previous_link").add(".first_link").addClass("no_more " + h) : i.children(".previous_link").add(".first_link").removeClass("no_more " + h)
        }
        var o = "current_page",
            v = "items_per_page",
            u, k = {
                item_container_id: ".content",
                items_per_page: 1,
                nav_panel_id: ".page_navigation",
                nav_info_id: ".info_text",
                num_page_links_to_display: 20,
                start_page: 0,
                wrap_around: !1,
                nav_label_first: "First",
                nav_label_prev: "Prev",
                nav_label_next: "Next",
                nav_label_last: "Last",
                nav_order: ["first", "prev", "num", "next", "last"],
                nav_label_info: "Showing {0}-{1} of {2} results",
                show_first_last: !0,
                abort_on_small_lists: !1,
                jquery_ui: !1,
                jquery_ui_active: "ui-state-highlight",
                jquery_ui_default: "ui-state-default",
                jquery_ui_disabled: "ui-state-disabled",
                show_paginate_if_one: !1
            },
            t = n.extend(k, t),
            l, r, e, i, c, s = t.jquery_ui ? t.jquery_ui_default : "",
            a = t.jquery_ui ? t.jquery_ui_active : "",
            h = t.jquery_ui ? t.jquery_ui_disabled : "";
        return this.each(function () {
            var it, tt, nt, k;
            if (r = n(this), l = n(this).find(t.item_container_id), e = r.find(t.item_container_id).children(), t.abort_on_small_lists && t.items_per_page >= e.size()) return r;
            if (u = r, u.data(o, 0), u.data(v, t.items_per_page), it = l.children().size(), tt = Math.ceil(it / t.items_per_page), tt != 1) {
                var rt = '<span class="ellipse more">...</span>',
                    ut = '<span class="ellipse less">...</span>',
                    ft = t.show_first_last ? '<a class="first_link ' + s + '" href="">' + t.nav_label_first + "</a>" : "",
                    et = t.show_first_last ? '<a class="last_link ' + s + '" href="">' + t.nav_label_last + "</a>" : "",
                    h = "";
                for (nt = 0; nt < t.nav_order.length; nt++) switch (t.nav_order[nt]) {
                case "first":
                    h += ft;
                    break;
                case "last":
                    h += et;
                    break;
                case "next":
                    h += '<a class="next_link ' + s + '" href="">' + t.nav_label_next + "</a>";
                    break;
                case "prev":
                    h += '<a class="previous_link ' + s + '" href="">' + t.nav_label_prev + "</a>";
                    break;
                case "num":
                    for (h += ut, k = 0; tt > k;) h += '<a class="page_link ' + s + '" href="" longdesc="' + k + '">' + (k + 1) + "</a>", k++;
                    h += rt
                }
                i = r.find(t.nav_panel_id), i.html(h).each(function () {
                    n(this).find(".page_link:first").addClass("first"), n(this).find(".page_link:last").addClass("last")
                }), i.children(".ellipse").hide(), i.find(".previous_link").next().next().addClass("active_page " + a), e.hide(), e.slice(0, u.data(v)).show(), c = r.find(t.nav_panel_id + ":first").children(".page_link").size(), t.num_page_links_to_display = Math.min(t.num_page_links_to_display, c), i.children(".page_link").hide(), i.each(function () {
                    n(this).children(".page_link").slice(0, t.num_page_links_to_display).show()
                }), r.find(".first_link").click(function (t) {
                    t.preventDefault(), p(n(this), 0), f(0)
                }), r.find(".last_link").click(function (t) {
                    t.preventDefault();
                    var i = c - 1;
                    y(n(this), i), f(i)
                }), r.find(".previous_link").click(function (t) {
                    t.preventDefault(), d(n(this))
                }), r.find(".next_link").click(function (t) {
                    t.preventDefault(), g(n(this))
                }), r.find(".page_link").click(function (t) {
                    t.preventDefault(), f(n(this).attr("longdesc"))
                }), f(parseInt(t.start_page)), w(), t.wrap_around || b()
            }
        })
    }
})(jQuery);

(function (a) {
    a.isScrollToFixed = function (b) {
        return !!a(b).data("ScrollToFixed")
    };
    a.ScrollToFixed = function (d, i) {
        var m = this;
        m.$el = a(d);
        m.el = d;
        m.$el.data("ScrollToFixed", m);
        var c = false;
        var H = m.$el;
        var I;
        var F;
        var k;
        var e;
        var z;
        var E = 0;
        var r = 0;
        var j = -1;
        var f = -1;
        var u = null;
        var A;
        var g;

        function v() {
            H.trigger("preUnfixed.ScrollToFixed");
            l();
            H.trigger("unfixed.ScrollToFixed");
            f = -1;
            E = H.offset().top;
            r = H.offset().left;
            if (m.options.offsets) {
                r += (H.offset().left - H.position().left)
            }
            if (j == -1) {
                j = r
            }
            I = H.css("position");
            c = true;
            if (m.options.bottom != -1) {
                H.trigger("preFixed.ScrollToFixed");
                x();
                H.trigger("fixed.ScrollToFixed")
            }
        }

        function o() {
            var J = m.options.limit;
            if (!J) {
                return 0
            }
            if (typeof (J) === "function") {
                return J.apply(H)
            }
            return J
        }

        function q() {
            return I === "fixed"
        }

        function y() {
            return I === "absolute"
        }

        function h() {
            return !(q() || y())
        }

        function x() {
            if (!q()) {
                var J = H[0].getBoundingClientRect();
                u.css({
                    display: H.css("display"),
                    width: J.width,
                    height: J.height,
                    "float": H.css("float")
                });
                cssOptions = {
                    "z-index": m.options.zIndex,
                    position: "fixed",
                    top: m.options.bottom == -1 ? t() : "",
                    bottom: m.options.bottom == -1 ? "" : m.options.bottom,
                    "margin-left": "0px"
                };
                if (!m.options.dontSetWidth) {
                    cssOptions.width = H.css("width")
                }
                H.css(cssOptions);
                H.addClass(m.options.baseClassName);
                if (m.options.className) {
                    H.addClass(m.options.className)
                }
                I = "fixed"
            }
        }

        function b() {
            var K = o();
            var J = r;
            if (m.options.removeOffsets) {
                J = "";
                K = K - E
            }
            cssOptions = {
                position: "absolute",
                top: K,
                left: J,
                "margin-left": "0px",
                bottom: ""
            };
            if (!m.options.dontSetWidth) {
                cssOptions.width = H.css("width")
            }
            H.css(cssOptions);
            I = "absolute"
        }

        function l() {
            if (!h()) {
                f = -1;
                u.css("display", "none");
                H.css({
                    "z-index": z,
                    width: "",
                    position: F,
                    left: "",
                    top: e,
                    "margin-left": ""
                });
                H.removeClass("scroll-to-fixed-fixed");
                if (m.options.className) {
                    H.removeClass(m.options.className)
                }
                I = null
            }
        }

        function w(J) {
            if (J != f) {
                H.css("left", r - J);
                f = J
            }
        }

        function t() {
            var J = m.options.marginTop;
            if (!J) {
                return 0
            }
            if (typeof (J) === "function") {
                return J.apply(H)
            }
            return J
        }

        function B() {
            if (!a.isScrollToFixed(H) || H.is(":hidden")) {
                return
            }
            var M = c;
            var L = h();
            if (!c) {
                v()
            } else {
                if (h()) {
                    E = H.offset().top;
                    r = H.offset().left
                }
            }
            var J = a(window).scrollLeft();
            var N = a(window).scrollTop();
            var K = o();
            if (m.options.minWidth && a(window).width() < m.options.minWidth) {
                if (!h() || !M) {
                    p();
                    H.trigger("preUnfixed.ScrollToFixed");
                    l();
                    H.trigger("unfixed.ScrollToFixed")
                }
            } else {
                if (m.options.maxWidth && a(window).width() > m.options.maxWidth) {
                    if (!h() || !M) {
                        p();
                        H.trigger("preUnfixed.ScrollToFixed");
                        l();
                        H.trigger("unfixed.ScrollToFixed")
                    }
                } else {
                    if (m.options.bottom == -1) {
                        if (K > 0 && N >= K - t()) {
                            if (!L && (!y() || !M)) {
                                p();
                                H.trigger("preAbsolute.ScrollToFixed");
                                b();
                                H.trigger("unfixed.ScrollToFixed")
                            }
                        } else {
                            if (N >= E - t()) {
                                if (!q() || !M) {
                                    p();
                                    H.trigger("preFixed.ScrollToFixed");
                                    x();
                                    f = -1;
                                    H.trigger("fixed.ScrollToFixed")
                                }
                                w(J)
                            } else {
                                if (!h() || !M) {
                                    p();
                                    H.trigger("preUnfixed.ScrollToFixed");
                                    l();
                                    H.trigger("unfixed.ScrollToFixed")
                                }
                            }
                        }
                    } else {
                        if (K > 0) {
                            if (N + a(window).height() - H.outerHeight(true) >= K - (t() || -n())) {
                                if (q()) {
                                    p();
                                    H.trigger("preUnfixed.ScrollToFixed");
                                    if (F === "absolute") {
                                        b()
                                    } else {
                                        l()
                                    }
                                    H.trigger("unfixed.ScrollToFixed")
                                }
                            } else {
                                if (!q()) {
                                    p();
                                    H.trigger("preFixed.ScrollToFixed");
                                    x()
                                }
                                w(J);
                                H.trigger("fixed.ScrollToFixed")
                            }
                        } else {
                            w(J)
                        }
                    }
                }
            }
        }

        function n() {
            if (!m.options.bottom) {
                return 0
            }
            return m.options.bottom
        }

        function p() {
            var J = H.css("position");
            if (J == "absolute") {
                H.trigger("postAbsolute.ScrollToFixed")
            } else {
                if (J == "fixed") {
                    H.trigger("postFixed.ScrollToFixed")
                } else {
                    H.trigger("postUnfixed.ScrollToFixed")
                }
            }
        }
        var D = function (J) {
            if (H.is(":visible")) {
                c = false;
                B()
            }
        };
        var G = function (J) {
            (!!window.requestAnimationFrame) ? requestAnimationFrame(B): B()
        };
        var C = function () {
            var K = document.body;
            if (document.createElement && K && K.appendChild && K.removeChild) {
                var M = document.createElement("div");
                if (!M.getBoundingClientRect) {
                    return null
                }
                M.innerHTML = "x";
                M.style.cssText = "position:fixed;top:100px;";
                K.appendChild(M);
                var N = K.style.height,
                    O = K.scrollTop;
                K.style.height = "3000px";
                K.scrollTop = 500;
                var J = M.getBoundingClientRect().top;
                K.style.height = N;
                var L = (J === 100);
                K.removeChild(M);
                K.scrollTop = O;
                return L
            }
            return null
        };
        var s = function (J) {
            J = J || window.event;
            if (J.preventDefault) {
                J.preventDefault()
            }
            J.returnValue = false
        };
        m.init = function () {
            m.options = a.extend({}, a.ScrollToFixed.defaultOptions, i);
            z = H.css("z-index");
            m.$el.css("z-index", m.options.zIndex);
            u = a("<div />");
            I = H.css("position");
            F = H.css("position");
            k = H.css("float");
            e = H.css("top");
            if (h()) {
                m.$el.after(u)
            }
            a(window).bind("resize.ScrollToFixed", D);
            a(window).bind("scroll.ScrollToFixed", G);
            if ("ontouchmove" in window) {
                a(window).bind("touchmove.ScrollToFixed", B)
            }
            if (m.options.preFixed) {
                H.bind("preFixed.ScrollToFixed", m.options.preFixed)
            }
            if (m.options.postFixed) {
                H.bind("postFixed.ScrollToFixed", m.options.postFixed)
            }
            if (m.options.preUnfixed) {
                H.bind("preUnfixed.ScrollToFixed", m.options.preUnfixed)
            }
            if (m.options.postUnfixed) {
                H.bind("postUnfixed.ScrollToFixed", m.options.postUnfixed)
            }
            if (m.options.preAbsolute) {
                H.bind("preAbsolute.ScrollToFixed", m.options.preAbsolute)
            }
            if (m.options.postAbsolute) {
                H.bind("postAbsolute.ScrollToFixed", m.options.postAbsolute)
            }
            if (m.options.fixed) {
                H.bind("fixed.ScrollToFixed", m.options.fixed)
            }
            if (m.options.unfixed) {
                H.bind("unfixed.ScrollToFixed", m.options.unfixed)
            }
            if (m.options.spacerClass) {
                u.addClass(m.options.spacerClass)
            }
            H.bind("resize.ScrollToFixed", function () {
                u.height(H.height())
            });
            H.bind("scroll.ScrollToFixed", function () {
                H.trigger("preUnfixed.ScrollToFixed");
                l();
                H.trigger("unfixed.ScrollToFixed");
                B()
            });
            H.bind("detach.ScrollToFixed", function (J) {
                s(J);
                H.trigger("preUnfixed.ScrollToFixed");
                l();
                H.trigger("unfixed.ScrollToFixed");
                a(window).unbind("resize.ScrollToFixed", D);
                a(window).unbind("scroll.ScrollToFixed", G);
                H.unbind(".ScrollToFixed");
                u.remove();
                m.$el.removeData("ScrollToFixed")
            });
            D()
        };
        m.init()
    };
    a.ScrollToFixed.defaultOptions = {
        marginTop: 0,
        limit: 0,
        bottom: -1,
        zIndex: 1000,
        baseClassName: "scroll-to-fixed-fixed"
    };
    a.fn.scrollToFixed = function (b) {
        return this.each(function () {
            (new a.ScrollToFixed(this, b))
        })
    }
})(jQuery);