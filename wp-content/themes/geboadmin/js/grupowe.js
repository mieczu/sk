$(document).ready(function () {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    rokChange();

    $('.rok').change(function () {
        rokChange();
    });

    $('.tabela').change(function () {
        tabelaChange();
    });

    $('#subcontractor').each(function () {
        addAutocomplete3($(this));
    });


    var dt_grupowe = $('#dt_grupowe').DataTable({
        "ajax": baseUrl + '/grupowe/?xhr=1',
        "order": [[0, "desc"]],
        "stateSave": true,
        "columnDefs": [
            {className: "nowrap upper", "targets": [5, 6, 7]},
            {className: "nowrap actions", "targets": [10]}
        ],
        "createdRow": function (row, data, dataIndex) {
            var now = new Date();
            var date = new Date(data[8]);
            var paid = new Date(data[9]);
            //alert(paid);
            if (paid.getTime()) {
                $(row).addClass('paid');
            } else if (now.getTime() == date.getTime()) {
                $(row).addClass('important');
            } else if (now.getTime() > date.getTime()) {
                $(row).addClass('important');
            }
        },
        "initComplete": function (settings, json) {
            $('#dt_grupowe .ajax-link').unbind();
            $('#dt_grupowe .ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);
                smoke.confirm('Potwierdź usunięcie?', function (a) {

                    if (a) {
                        delete_link(e, link, dt_grupowe);
                    } else {
                        //smoke.alert('"no way" pressed', {ok:"close"});
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_grupowe .ajax-link').unbind();
        $('#dt_grupowe .ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {

                if (a) {
                    delete_link(e, link, dt_grupowe);
                } else {
                    //smoke.alert('"no way" pressed', {ok:"close"});
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    });

    dt_grupowe.column( 3 ).visible( false );

    var filter_header =
        '<tr>' +
            '<td colspan="7">' +
                '<table border="0" cellspacing="5" cellpadding="5">' +
                '<tr>' +
                    '<td>Wszystie:</td>' +
                    '<td><input type="radio" id="all" name="radio1" value="0"></td>' +
                    '<td>Nie zapłacone:</td>' +
                    '<td><input type="radio" id="not_paid" name="radio1" value="1"></td>' +
                    '<td>Zapłacone:</td>' +
                    '<td><input type="radio" id="max_paid" name="radio1" value="2"></td>' +
                '</tr>' +
                '</table>' +
            '</td>' +
        '</tr>';
    $('#dt_grupowe thead tr').before(filter_header);

    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var selectedVal = "";
            var selected = $("input[type='radio'][name='radio1']:checked");
            if (selected.length > 0) {
                selectedVal = selected.val();
            }

            if (selectedVal == 0 ){
                return true;
            }else if(selectedVal == 1) {
                var paid = new Date(data[9]);

                if (paid.getTime()) {
                    return false;
                }else {
                    return true;
                }
            }else if(selectedVal == 2) {
                var paid = new Date(data[9]);

                if (paid.getTime()) {
                    return true;
                }else {
                    return false;
                }
            }
        }
    );
    $("input[type='radio'][name='radio1']").click( function() {
        dt_grupowe.draw();
    });



    var validator = $('.form_grupowa').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        highlight: function (element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            var div = $(element).closest('div');
            div.removeClass("f_error");
            div.children(".error").remove();
        },
        errorPlacement: function (error, element) {
            $(element).closest('div').append(error);
        },
        rules: {
            value: {required: true},
            fnumer: {required: true},
            vat: {required: true},
            date_payment: {required: true},
            subcontractor: {required: true}
        },
        messages: {
            //transport: {required: "Pole wymagane"},
            value: {required: "Pole wymagane"},
            fnumer: {required: "Pole wymagane"},
            vat: {required: "Pole wymagane"},
            date_payment: {required: "Pole wymagane"},
            subcontractor: {required: "Pole wymagane"}

        },
        invalidHandler: function (form, validator) {
            $.sticky("Popraw błędnie wypełnione pola w formularzu", {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });


    jQuery.datetimepicker.setLocale('pl');

    $('#date_payment').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#date_paid').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#date_received').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
});

function addAutocomplete3(element) {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    element.autocomplete({
        minLength: 2,
        source: baseUrl + "/podwykonawcy/?action=autocomplete",
        focus: function (event, ui) {
            return false;
        },
        select: function (event, ui) {
            $(this).val(ui.item.name);
            $( "#idsubcontractor" ).val( ui.item.id );

            return false;
        }
    })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.name + "</a>")
            .appendTo(ul);
    };
}

function tabelaChange() {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    $('#loading_animation').show();
    $.ajax({
        type: "POST",
        url: baseUrl + "nbp/?action=get",
        data: {file: $('#tabela').val()},
        dataType: "json",
        success: function (msg) {
            $('#loading_animation').hide();
            //alert(tr.find('.wal').val());
            $('#usd').val(msg.usd.kurs_sredni);
            $('#eur').val(msg.eur.kurs_sredni);


        },
        error: function () {
            $('#loading_animation').hide();
            $.sticky("Nie można pobrać kursu walut NBP.", {autoclose: false, position: "top-center", type: "st-error"});
        }
    });
}

function rokChange() {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }
    if (!isNaN($('#rok').val())) {

        $('#loading_animation').show();
        var tb = $('#tabela');
        tb.find('option:not(:first)').remove();
        $.ajax({
            type: "POST",
            url: baseUrl + "nbp/",
            data: {rok: $('#rok').val()},
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                $.each(msg, function (i, item) {
                    tb.append($('<option>', {
                        value: item.val,
                        text: item.sub
                    }));
                });
            },
            error: function () {
                $('#loading_animation').hide();
                $.sticky("Nie można pobrać kursu walut NBP.", {
                    autoclose: false,
                    position: "top-center",
                    type: "st-error"
                });
            }
        });
    }
}