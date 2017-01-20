$(document).ready(function(){

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

    var dt_korekty = $('#dt_korekty').DataTable({
        "ajax": baseUrl + '/korekty/?xhr=1',
        "order": [[0, "desc"]],
        "stateSave": true,
        "columnDefs": [
            {className: "nowrap upper", "targets": [4, 5]},
            {className: "nowrap actions", "targets": [9]}
        ],
        //"createdRow": function (row, data, dataIndex) {
        //    var now = new Date();
        //    var date = new Date(data[8]);
        //    var paid = new Date(data[9]);
        //    //alert(paid);
        //    if (paid.getTime()) {
        //        $(row).addClass('paid');
        //    } else if (now.getTime() == date.getTime()) {
        //        $(row).addClass('important');
        //    } else if (now.getTime() > date.getTime()) {
        //        $(row).addClass('important');
        //    }
        //},
        "initComplete": function (settings, json) {
            $('#dt_korekty .ajax-link').unbind();
            $('#dt_korekty .ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);
                smoke.confirm('Potwierdź usunięcie?', function (a) {

                    if (a) {
                        delete_link(e, link, dt_korekty);
                    } else {
                        //smoke.alert('"no way" pressed', {ok:"close"});
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_korekty .ajax-link').unbind();
        $('#dt_korekty .ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {

                if (a) {
                    delete_link(e, link, dt_korekty);
                } else {
                    //smoke.alert('"no way" pressed', {ok:"close"});
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    });
});

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
