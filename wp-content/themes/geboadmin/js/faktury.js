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

    (function ($) {
        $.widget("custom.combobox", {
            _create: function () {
                this.wrapper = $("<span>")
                    .addClass("custom-combobox")
                    .insertAfter(this.element);

                this.element.hide();
                this._createAutocomplete();
                this._createShowAllButton();
            },

            _createAutocomplete: function () {
                var selected = this.element.children(":selected"),
                    value = selected.val() ? selected.text() : "";

                this.input = $("<input>")
                    .appendTo(this.wrapper)
                    .val(value)
                    .attr("title", "")
                    .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({
                        tooltipClass: "ui-state-highlight"
                    });

                this._on(this.input, {
                    autocompleteselect: function (event, ui) {
                        ui.item.option.selected = true;
                        this._trigger("select", event, {
                            item: ui.item.option
                        });
                    },

                    autocompletechange: "_removeIfInvalid"
                });
            },

            _createShowAllButton: function () {
                var input = this.input,
                    wasOpen = false;

                $("<a>")
                    .attr("tabIndex", -1)
                    .attr("title", "Show All Items")
                    .tooltip()
                    .appendTo(this.wrapper)
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass("ui-corner-all")
                    .addClass("custom-combobox-toggle ui-corner-right")
                    .mousedown(function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .click(function () {
                        input.focus();

                        // Close if already visible
                        if (wasOpen) {
                            return;
                        }

                        // Pass empty string as value to search for, displaying all results
                        input.autocomplete("search", "");
                    });
            },

            _source: function (request, response) {
                var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                response(this.element.children("option").map(function () {
                    var text = $(this).text();
                    if (this.value && ( !request.term || matcher.test(text) ))
                        return {
                            label: text,
                            value: text,
                            option: this
                        };
                }));
            },

            _removeIfInvalid: function (event, ui) {

                // Selected an item, nothing to do
                if (ui.item) {
                    return;
                }

                // Search for a match (case-insensitive)
                var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
                this.element.children("option").each(function () {
                    if ($(this).text().toLowerCase() === valueLowerCase) {
                        this.selected = valid = true;
                        return false;
                    }
                });

                // Found a match, nothing to do
                if (valid) {
                    return;
                }

                // Remove invalid value
                this.input
                    .val("")
                    .attr("title", value + " didn't match any item")
                    .tooltip("open");
                $('.nip.input-prepend .add-on').text('');
                $('#vies').attr('disabled', true);
                $('#vies2').attr('disabled', true);
                $('#gus').attr('disabled', true);

                this.element.val("");
                this._delay(function () {
                    this.input.tooltip("close").attr("title", "");
                }, 2500);
                this.input.autocomplete("instance").term = "";
            },

            _destroy: function () {
                this.wrapper.remove();
                this.element.show();
            }
        });
    })(jQuery);

    $('#addFaktura #order').combobox({
        select: function (event, ui) {
            $.ajax({
                method: "POST",
                url: baseUrl + '/klienci/',
                data: {action: "get", id: $(this).val()},
                dataType: "json"
            }).success(function (msg) {
                    $('#addFaktura #client').val(msg.id);
                    $('#client').siblings('.custom-combobox').children('input').val(msg.short_name);
                });

            //
            //$('#addFaktura #client').val(9).combobox('refresh');
            //$('.custom-combobox-input').focus().val('PKP ');
            //$('.ui-autocomplete-input').autocomplete('close');

            //alert($(this).val());
        }
    });

    $('#addFaktura #parent').combobox({

        select: function (event, ui) {
            var my_select = $(this);
            $.ajax({
                method: "POST",
                url: baseUrl + '/grupowe/',
                data: {action: "get", id: $(this).val()},
                dataType: "json"
            }).success(function (msg) {
                console.debug(msg);

                smoke.confirm('Potwierdź zastąpienie danych danymi z faktury grupowej?', function (a) {

                    if (a) {
                        $('#date_payment').val(msg.date_payment);
                        $('#date_received').val(msg.date_received);
                        $('#date_paid').val(msg.date_paid);
                        $('#eur').val(msg.eur/10000);
                        $('#usd').val(msg.usd/10000);
                        $('#fnumer').val(msg.numer);
                    } else {
                        $('#parent').siblings('.custom-combobox').children('input').val('');
                        $('#parent').val('');
                    }
                }, {ok: "Potwierdzam", cancel: "Anuluj"})


                //$('#addFaktura #client').val(msg.id);
                //$('#client').siblings('.custom-combobox').children('input').val(msg.short_name);
            });
        }

    });


    $('#addFaktura #client').combobox();


    $('#order').siblings('.custom-combobox').children('input').addClass('combobox-order');
    $('#client').siblings('.custom-combobox').children('input').addClass('combobox-client');
    $('#parent').siblings('.custom-combobox').children('input').addClass('combobox-order');


    var dt_faktury = $('#dt_faktury').DataTable({
        "ajax": baseUrl + '/faktury/?xhr=1',
        "order": [[0, "desc"]],
        "stateSave": true,
        "columnDefs": [
            {className: "nowrap upper", "targets": [5, 6, 7]},
            {className: "nowrap actions", "targets": [11]}
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
            $('#dt_faktury .ajax-link.delete').unbind();
            $('#dt_faktury .ajax-link.delete').click(function (e) {
                e.preventDefault();
                var link = $(this);
                smoke.confirm('Potwierdź usunięcie?', function (a) {

                    if (a) {
                        delete_link(e, link, dt_faktury);
                    } else {
                        //smoke.alert('"no way" pressed', {ok:"close"});
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_faktury .ajax-link.delete').unbind();
        $('#dt_faktury .ajax-link.delete').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {

                if (a) {
                    delete_link(e, link, dt_faktury);
                } else {
                    //smoke.alert('"no way" pressed', {ok:"close"});
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    });

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
    $('#dt_faktury thead tr').before(filter_header);

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
        dt_faktury.draw();
    });





    var dt_faktury2 = $('#dt_faktury2').DataTable({
        "ajax": baseUrl + '/faktury/?xhr=2',
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
            if (paid.getTime()) {
                $(row).addClass('paid');
            } else if (now.getTime() == date.getTime()) {
                $(row).addClass('important');
            } else if (now.getTime() > date.getTime()) {
                $(row).addClass('important');
            }
        },
        "initComplete": function (settings, json) {
            $('#dt_faktury2 .ajax-link').unbind();
            $('#dt_faktury2 .ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);
                smoke.confirm('Potwierdź usunięcie?', function (a) {

                    if (a) {
                        delete_link(e, link, dt_faktury2);
                    } else {
                        //smoke.alert('"no way" pressed', {ok:"close"});
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_faktury2 .ajax-link').unbind();
        $('#dt_faktury2 .ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {

                if (a) {
                    delete_link(e, link, dt_faktury2);
                } else {
                    //smoke.alert('"no way" pressed', {ok:"close"});
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    });

    $('#dt_faktury2 thead tr').before(filter_header);

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
        dt_faktury2.draw();
    });


    var validator = $('.form_faktura').validate({
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
            date_payment: {required: true}
            //typ: {required: true},
            //destination: {required: true},
            //orgin: {required: true},
            //eta: {
            //    isDT: true
            //}
        },
        messages: {
            date_payment: {required: "Pole wymagane"}
            //typ: {required: "Pole wymagane"},
            //destination: {required: "Pole wymagane"},
            //orgin: {required: "Pole wymagane"},
            //eta: {
            //    idDT: "To nie jest poprawna data "
            //}
        },
        invalidHandler: function (form, validator) {
            $.sticky("Popraw błędnie wypełnione pola w formularzu", {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });


    $('.form_faktura').submit(function (e) {

        if ($('#order').val() == 0) {
            if (!$('#old').prop('checked')) {
                e.preventDefault();
                validator.showErrors({
                    "order": "Musisz wybrać zlecenie!"
                });
            }
        }

        if ($('#client').val() == 0) {

            e.preventDefault();
            validator.showErrors({
                "client": "Musisz wybrać klienta!"
            });
        }

        if ($('#dt_pozycje tr .subc').length < 1) {

            e.preventDefault();
            $('.dataTables_empty').addClass('f_error');
            $('.dataTables_empty').html('<label class="error">Musisz dodać conajmniej jedną pozycję do faktury.</label>');
        }

    });

    var validator2 = $('.form_faktura2').validate({
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
            date_payment: {required: true}
        },
        messages: {
            //transport: {required: "Pole wymagane"},
            value: {required: "Pole wymagane"},
            fnumer: {required: "Pole wymagane"},
            vat: {required: "Pole wymagane"},
            date_payment: {required: "Pole wymagane"}

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


    var dt_pozycje1 = $('#dt_pozycje').DataTable({
        "order": [[0, "desc"]],
        "paging": false,
        "ordering": false,
        "info": false,
        "stateSave": true,
        "oLanguage": {
            "sEmptyTable": "Brak pozycji na fakturze"
        },
        "initComplete": function (settings, json) {
            $('.ajax-link').click(function (e) {
                e.preventDefault();
                dt_pozycje1.row($(this).parents('tr'))
                    .remove()
                    .draw(false);
                //delete_link(e, $(this), dt_pozycje1);
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        //$('.ajax-link').click(function(e){
        //    delete_link(e,$(this),table);
        //});
    });

    var dt_pozycje2 = $('#dt_pozycje2').DataTable({
        "order": [[0, "desc"]],
        "paging": false,
        "ordering": false,
        "info": false,
        "stateSave": true,
        "oLanguage": {
            "sEmptyTable": "Brak pozycji na fakturze"
        },
        "initComplete": function (settings, json) {
            $('#dt_pozycje2 .ajax-link').unbind();
            $('#dt_pozycje2 .ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);
                smoke.confirm('Potwierdź usunięcie?', function (a) {
                    if (a) {
                        delete_link(e, link, dt_pozycje2);
                    } else {
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})

            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_pozycje2 .ajax-link').unbind();
        $('#dt_pozycje2 .ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {
                if (a) {
                    delete_link(e, link, dt_pozycje2);
                } else {
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})

        });
    });


    $('#subAdd').click(function () {
        var msg = {id: $('#dt_pozycje tr .subc').length + 1};
        //alert($('#dt_pozycje tr .subc').length);
        dt_pozycje1.row.add([
            '<input class="subc" style="width: 550px;" id="row[' + msg.id + '][subc]" name="row[' + msg.id + '][subc]" value="" type="text"/>',
            '<input class="quantity" style="width: 150px;" id="row[' + msg.id + '][quantity]" name="row[' + msg.id + '][quantity]" value="" type="text">',
            '<input class="value" style="width: 100px;" id="row[' + msg.id + '][value]" name="row[' + msg.id + '][value]" value="" type="text">',

            '<input class="vat" style="width: 40px;" id="row[' + msg.id + '][vat]" name="row[' + msg.id + '][vat]" value="" type="text">',
            '<select class="wal" style="width: 75px;" id="row[' + msg.id + '][wal]" name="row[' + msg.id + '][wal]">' +
            '<option value="eur">EURO</option>' +
            '<option value="usd">USD</option>' +
            '<option value="pln" selected="selected">PLN</option>' +
            '</select>',
            '<input class="id_sub" id="row[' + msg.id + '][id_sub]" name="row[' + msg.id + '][id_sub]" value="' + msg.id + '" type="hidden">' +
            '<input class="id" id="row[' + msg.id + '][id]" name="row[' + msg.id + '][id]" value="" type="hidden">' +
            '<input class="id_order" id="row[' + msg.id + '][id_order]" name="row[' + msg.id + '][id_order]" value="" type="hidden">' +
            '<a href="' + baseUrl + '/pozycje?action=delete_sub&id=' + msg.id + '" class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>'
        ]).draw(false);

        $('.ajax-link').click(function (e) {
            e.preventDefault();
            dt_pozycje1.row($(this).parents('tr'))
                .remove()
                .draw(false);
            //delete_link(e, $(this), dt_pozycje1);
        });

        $('.subc').each(function () {
            addAutocomplete2($(this));
        });
    });

    $('#subAdd2').click(function () {
        $('#loading_animation').show();
        $.ajax({
            type: "POST",
            url: baseUrl + "faktury/?action=new_sub",
            data: {id: $('#id').val()},
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                if (msg.status == 'success') {
                    dt_pozycje2.row.add([
                        '<input class="subc" style="width: 550px;" id="row[' + msg.id + '][subc]" name="row[' + msg.id + '][subc]" value="" type="text"/>',
                        '<input class="quantity" style="width: 150px;" id="row[' + msg.id + '][quantity]" name="row[' + msg.id + '][quantity]" value="" type="text">',
                        '<input class="value" style="width: 100px;" id="row[' + msg.id + '][value]" name="row[' + msg.id + '][value]" value="" type="text">',

                        '<input class="vat" style="width: 40px;" id="row[' + msg.id + '][vat]" name="row[' + msg.id + '][vat]" value="" type="text">',
                        '<select class="wal" style="width: 75px;" id="row[' + msg.id + '][wal]" name="row[' + msg.id + '][wal]">' +
                        '<option value="eur">EURO</option>' +
                        '<option value="usd">USD</option>' +
                        '<option value="pln" selected="selected">PLN</option>' +
                        '</select>',
                        '<input class="id_sub" id="row[' + msg.id + '][id_sub]" name="row[' + msg.id + '][id_sub]" value="' + msg.id + '" type="hidden">' +
                        '<input class="id" id="row[' + msg.id + '][id]" name="row[' + msg.id + '][id]" value="" type="hidden">' +
                        '<input class="id_order" id="row[' + msg.id + '][id_order]" name="row[' + msg.id + '][id_order]" value="" type="hidden">' +
                        '<a href="' + baseUrl + '/pozycje?action=delete_sub&id=' + msg.id + '"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>'
                    ]).draw(false);

                    $.sticky("Dodano towar.", {position: "top-center", type: "st-success"});

                    $('.subc').each(function () {
                        addAutocomplete2($(this));
                    });

                    $('.name').each(function () {
                        addAutocompletePoz($(this));
                    });


                }
            },
            error: function () {
                $('#loading_animation').hide();
                $.sticky("Nie dodano poddostawcy.", {autoclose: false, position: "top-center", type: "st-error"});
            }
        });

    })

    $('#subSave2').click(function () {
        $('#loading_animation').show();
        var data = dt_pozycje2.$('input, select').serialize();
        $.ajax({
            type: "POST",
            url: baseUrl + "faktury/?action=save_sub",
            data: data,
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                console.log(msg.status);
                if (msg.status == 'error') {
                    $.sticky("Błąd: nie można zapisać towarów id:" + msg.id, {
                        autoclose: false,
                        position: "top-center",
                        type: "st-error"
                    });
                } else if (msg.status == 'success') {
                    $.sticky("Zapisano podwykonawców", {autoclose: false, position: "top-center", type: "st-success"});
                } else {
                    $.sticky("Nieoczekiwany błąd", {autoclose: false, position: "top-center", type: "st-error"});
                }
            },
            error: function (msg) {
                $('#loading_animation').hide();
                $.sticky("Błąd połączenia podczas zapisywania podwykonawców", {
                    autoclose: false,
                    position: "top-center",
                    type: "st-error"
                });
            }
        });
        return false;
    });


    $('.subc').each(function () {
        addAutocomplete2($(this));
    });
});

function addAutocomplete2(element) {
    var getUrl = window.location;
    var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

    element.autocomplete({
        minLength: 2,
        source: baseUrl + "/pozycje/?action=autocomplete",
        focus: function (event, ui) {
            return false;
        },
        select: function (event, ui) {
            $(this).val(ui.item.name);
            //var tr = $(this).closest('tr');
            //tr.find('.id').val(ui.item.id);

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
