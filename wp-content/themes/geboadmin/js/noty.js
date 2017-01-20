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


    var dt_noty = $('#dt_noty').DataTable({
        "ajax": baseUrl + 'noty/?xhr=1',
        "order": [[0, "desc"]],
        "columnDefs": [
            {className: "nowrap", "targets": [6]},

        ],
        "initComplete": function (settings, json) {
            $('#dt_noty .ajax-link').unbind();
            $('#dt_noty .ajax-link').click(function (e) {
                delete_link(e, $(this), dt_noty);
            });
        },
        "createdRow": function (row, data, dataIndex) {
            var now = new Date();
            var date = new Date(data[8]);
            var paid = new Date(data[9]);
            var clo = new Date(data[10]);

            if (paid.getTime() && clo.getTime()) {
                $(row).addClass('paid');
            } else if (paid.getTime()) {
                if (data[5] == 'Transportowa') {
                    $(row).addClass('paid');
                } else {
                    $(row).addClass('clo1');
                }
            } else {
                if (now.getTime() == date.getTime()) {
                    $(row).addClass('important');
                } else if (now.getTime() > date.getTime()) {
                    $(row).addClass('important');
                }
            }
        },
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_noty .ajax-link').unbind();
        $('#dt_noty .ajax-link').click(function (e) {
            delete_link(e, $(this), dt_noty);
        });
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
                console.log(this);
                this.input
                    .val("")
                    .attr("title", value + " didn't match any item")
                    .tooltip("open");
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

    $('#zlecenie').combobox();
    $('#klient').combobox();


    var validator = $('.form_order').validate({
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
            typ: {required: true},
            idklient: {required: true},
            idzlecenie: {required: true},
            lang: {required: true}

        },
        messages: {
            transport: {required: "Pole wymagane"},
            typ: {required: "Pole wymagane"},
            lang: {required: "Pole wymagane"}
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
    $('#sad_date').datetimepicker({
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
    $('#date_paid2').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#date_payment').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#date_paidtr').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#date_paymenttr').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });

    $('input[name=typ]').on('change', function () {
        if ($(this).val() == 'Księgowa') {
            //alert($(this).val());
            $('#tr').hide();
            $('#content').text('');
            $('#ks').show();

        }

        if ($(this).val() == 'Transportowa') {
            //alert($(this).val());
            $('#ks').hide();
            $('#ks input').each(function () {
                $(this).val('');
            });

            $('#tr').show();
        }

    });


});

function tabelaChange(element) {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    $('#loading_animation').show();
    var tr = element.closest('tr');
    $.ajax({
        type: "POST",
        url: baseUrl + "nbp/?action=get",
        data: {file: element.val()},
        dataType: "json",
        success: function (msg) {
            $('#loading_animation').hide();
            //alert(tr.find('.wal').val());
            switch (tr.find('.wal').val()) {
                case 'usd':
                    console.log(msg.usd.kurs_sredni);
                    tr.find('.exch').val(msg.usd.kurs_sredni);
                    break;
                case 'eur':
                    console.log(msg.eur.kurs_sredni);
                    tr.find('.exch').val(msg.eur.kurs_sredni);
                    break;
                case 'pln':
                    tr.find('.exch').val(1);
                    break;
                default:

            }

        },
        error: function () {
            $('#loading_animation').hide();
            $.sticky("Nie można pobrać kursu walut NBP.", {autoclose: false, position: "top-center", type: "st-error"});
        }
    });
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
            $.sticky("Nie można pobrać kursu walut NBP.", {autoclose: false, position: "top-center", type: "st-error"});
        }
    });
}
