requirejs.config({
    baseUrl: 'js/lib',
    paths: {
        jquery: "jquery-2.1.4.min",
        underscore: "underscore-min",
        'datatables': "jquery.dataTables.min",
        'moment': "moment-with-locales.min"
    },
    shim: {
        'bootstrap.min': ['jquery'],
        'bootstrap-datetimepicker.min': ['jquery'],
        'fontawesome-iconpicker.min': ['jquery'],
        'jquery.geocomplete.min': ['jquery'],
        'jquery.fileupload': ['jquery'],
        'jquery.fileupload-process': ['jquery'],
        'jquery.fileupload-validate1': ['jquery'],
        'tinymce.min': ['jquery']
    }
});
requirejs(['jquery', 'underscore', 'datatables', 'moment', 'bootstrap.min', 'bootstrap-datetimepicker.min', 'fontawesome-iconpicker.min', 'jquery.geocomplete.min', 'jquery.fileupload', 'jquery.fileupload-process', 'jquery.fileupload-process', 'jquery.fileupload-validate', 'tinymce.min'], function ($) {
    var grid = $("#grid").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "php/GetCalendar.php?mode=2",
        "columnDefs": [{
                "targets": [5, 8],
                "visible": false,
                "searchable": false
						}, {
                "targets": -1,
                "data": null,
                "orderable": false,
                "defaultContent": "<div class='iActions'><i title='send' class='fa fa-envelope send'></i><i title='update' class='fa fa-circle-o-notch update'></i><i title='delete' class='fa fa-trash delete'></i></div>"
						}
		],
        // icons column
        "createdRow": function (row, data, index) {
            var $cell = $("td", row).eq(3);
            var iconName = $cell.text();
            $cell.text("");
            $cell.append("<i class='fa " + iconName + "'></i>");

        }
    });

    $.get('php/CreateToken.php', function (t) {
        $('#tk').val(t);
    });

    // add and update actions
    $('#grid tbody').on('click', '.iActions i', function (e) {
        // check action
        var update = $(e.target).hasClass('update');
        var deleteEv = $(e.target).hasClass('delete');
        var data = grid.row($(this).parents('tr')).data();

        // set values to update action
        if (update) {
            clearData();
            $('#date input').val(data[1] + ' ' + data[2]);
            $('#rDate').val(data[1]);
            $('#rTime').val(data[2]);
            $('#icon').val(data[3]);
            $('#title').val(data[4]);
            tinyMCE.get('text').setContent(_.unescape(data[5]));
            $('#location').val(data[6]);
            $('#preview').val(data[7]);
            $('#weather').val(data[8]);
            $('.iconpicker-component i').removeClass().addClass('fa ' + data[3]);

            $('#eventModal').modal('show').data({
                'action': '2',
                'event': data[0]
            });
            console.log(data);
        } else if (deleteEv) {
            $('#eventDeleteModal').modal('show').data('event', data[0]);
        } else {
            $('#subscriptionsModal .alert').remove();
            $.getJSON("php/GetCalendar.php", {
                event: data[0],
                mode: 3
            }, function (subscriptions) {
                console.log(subscriptions);
                $.each(subscriptions, function (i) {
                    var item = '<div class="alert alert-warning alert-dismissible fade in" role="alert"> \
									  	<strong> ' + subscriptions[i].email + ' &nbsp;</strong> ' + subscriptions[i].name + ' \
									</div>';
                    $('#subscriptionsModal .modal-body').prepend(item);
                });
                $('#subscriptionsModal .badge').text(subscriptions.length);
                if (subscriptions.length > 0)
                    $('#subscriptionsModal').modal('show').data('event', data[0]);
                else
                    $('#infoModal').modal('show');
            });
        }
    });

    // Initialize datetime picker
    $("#date").datetimepicker({
        format: "DD/MM/YYYY HH:mm",
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
        // separates date and time values
    }).on('dp.change', function (e) {
        var date = $(this).children('input').val().split(' ');
        $('#rDate').val(date[0]);
        $('#rTime').val(date[1]);
        console.log(date);
    });

    // Initialize icon picker
    $('#icon').iconpicker({
        placement: 'bottom'
    });

    // Initialize maps search
    $('#location').geocomplete().on('change blur', function () {
        var $this = $(this);
        setTimeout(function () {
            var location = $this.val();
            $('#weather').val(location);
        }, 270);
    });

    // opens modal for add new record
    $('#new').on('click', function () {
        clearData();
        $('#eventModal').modal('show').data('action', '1');
    });

    // deletes a record
    $('#delete').on('click', function () {
        var id = $('#eventDeleteModal').data('event');
        $.post('php/UpdateCalendar.php', {
            event_id: id,
            action: 3
        }, function (e) {
            grid.draw(false);
            $('#eventDeleteModal').modal('hide');
        });
    });

    // updates a record
    $('#save').on('click', function () {
        var $form = $('#eventModal');
        var check = checkData($form);
        var url;
        // check if the fields are not empty
        if (check) {
            $('.img-loader').show();
            var action = $('#eventModal').data('action');
            var content = '&action=' + action + '&text=' + tinyMCE.get('text').getContent();
            var newEvent = $form.find('form').serialize() + content;
            url = "php/UpdateCalendar.php";
            if (action == "2")
                newEvent += "&event_id=" + $('#eventModal').data('event');
            $.post(url, newEvent, function (e) {
                console.log(e);
                grid.draw(false);
                $('#eventModal').modal('hide');
                $('.img-loader').hide();
            });
        }
    });

    $('#send').on('click', function () {
        var event = $('#subscriptionsModal').data('event');
        var url = "php/SendEmail.php";
        $('#loader').fadeIn();
        $.post(url, {
            event: event,
            token: $('#subscriptionsModal input[type="hidden"]').val()
        }, function (e) {
            $('#subscriptionsModal').modal('hide');
            $('#loader').fadeOut();
        });
    });

    // remove error class
    $('#eventModal input').on('focus', function () {
        $(this).parent('div').removeClass('bg-danger');
    });

    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = "php/",
        uploadButton = $('<button/>')
        .addClass('btn btn-primary')
        .prop('disabled', true)
        .text('Processing...')
        .on('click', function () {
            var $this = $(this),
                data = $this.data();
            $this
                .off('click')
                .text('Abort')
                .on('click', function () {
                    $this.remove();
                    data.abort();
                });
            data.submit().always(function () {
                $this.remove();
            });
        });
    $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            autoUpload: false,
            acceptFileTypes: /(\.|\/)(ics)$/i,
            maxFileSize: 999000,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
            previewCrop: false
        }).on('fileuploadadd', function (e, data) {
            data.context = $('<div/>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<p/>')
                    .append($('<span/>').text(file.name));
                if (!index) {
                    node
                        .append('<br>')
                        .append(uploadButton.clone(true).data(data));
                }
                node.appendTo(data.context);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = $(data.context.children()[index]);
            if (file.preview) {
                node
                    .prepend('<br>')
                    .prepend(file.preview);
            }
            if (file.error) {
                node
                    .append('<br>')
                    .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                    .text('Upload')
                    .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            $('#progress .progress-bar').css('width', 0);
            var progress = parseInt(data.loaded / data.total * 100, 10);
            setTimeout(function () {
                $('#progress .progress-bar').css('width', progress + '%');
            }, 700);
        }).on('fileuploaddone', function (e, data) {
            console.log(e);
            console.log(data);
            $.post("php/UpdateCalendar.php", {
                name: data.result.files[0].name,
                action: 4
            }, function (response) {
                console.log(response);
                grid.draw(false);
            })
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('File upload failed.');
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            });
        }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');


    tinymce.init({
        selector: "#text",
        menubar: false,
        plugins: [
			 "advlist autolink link image media lists hr",
			 "table directionality template paste"
		],
        toolbar: "styleselect | bold italic |  alignleft aligncenter alignright alignjustify | link image media | bullist numlist",
        image_advtab: false
    });

    $(document).on('focusin', function (event) {
        if ($(event.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    // check the fields content length

    function checkData($form) {
        var result = true;
        var $inputs = $form.find('input').not('.iconpicker-search');
        $inputs.each(function () {
            var $input = $(this);
            if ($input.val().length == 0) {
                $input.parent('div').addClass('bg-danger');
                result = false;
            }
        });

        return result;
    }

    // clears the container inputs
    function clearData() {
        tinyMCE.get('text').setContent('');
        $('#eventModal').find('input').val('');
    }
});