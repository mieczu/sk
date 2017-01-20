/**
 * Created by mieczu on 2016-04-28.
 */

$('#date_start').datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false
});
$('#date_end').datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    scrollMonth: false,
    scrollTime: false,
    scrollInput: false
});

$.validator.addMethod('isDT', function (value) {
    var re = new RegExp("^([0-9]{4}-[0-9]{2}-[0-9]{2})$");
    if (re.test(value) || value.length == 0) {
        return true;
    } else {
        return false;
    }
}, "To nie jest poprawna data");

var validator = $('#search_order').validate({
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
        date_start: {
            required: true,
            isDT: true
        },
        date_end: {
            required: true,
            isDT: true
        }
    },
    messages: {
        date_start: {
            required: "Pole wymagane",
            idDT: "To nie jest poprawna data "
        },
        date_end: {
            required: "Pole wymagane",
            idDT: "To nie jest poprawna data "
        }
    },
    invalidHandler: function (form, validator) {
        //$.sticky("Popraw błędnie wypełnione pola w formularzu", {
        //    autoclose: false,
        //    position: "top-center",
        //    type: "st-error"
        //});
    }
});




$(document).ready(function () {
    var dt_r_klienci = $('#dt_r_klienci').DataTable({
        'bFilter':false,
        "bPaginate": false
    });

    var dt_r_koszty = $('#dt_r_koszty').DataTable({
        'bFilter':false,
        "bPaginate": false
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


    $('#client').combobox();

    $('#pdf').click(function () {
        if ($('#pdf').attr('checked')) {
            $('#rep1').attr('action', '?action=zlecenia_pdf');
            $('#rep1').attr('target', '_blank');
        } else {
            $('#rep1').attr('action', '?action=zlecenia');
            $('#rep1').attr('target', '_self');
        }

        if ($('#pdf').attr('checked')) {
            $('#rep2').attr('action', '?action=klienci_pdf');
            $('#rep2').attr('target', '_blank');
        } else {
            $('#rep2').attr('action', '?action=klienci');
            $('#rep2').attr('target', '_self');
        }


    });

});

