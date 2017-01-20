$(document).ready(function() {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4){
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    }else{
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    var dt_klienci = $('#dt_klienci').DataTable({
        "ajax": baseUrl + '/klienci/?xhr=1',
        "order": [[ 0, "desc" ]],
        "initComplete": function (settings, json) {
            $('.ajax-link').unbind();
            $('.ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);

                smoke.confirm('Potwierdź usunięcie?', function (a) {
                    if (a) {
                        delete_link(e, link, dt_klienci);
                    } else {
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
    }).on( 'draw.dt', function (a, settings, data) {
        $('.ajax-link').unbind();
        $('.ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);

            smoke.confirm('Potwierdź usunięcie?', function (a) {
                if (a) {
                    delete_link(e, link, dt_klienci);
                } else {
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    } );

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
                $('#vies').attr('disabled',true);
                $('#vies2').attr('disabled',true);
                $('#gus').attr('disabled',true);
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



    $('#kraj').combobox({
        select: function (event, ui) {
            //alert($(this).val());
            $('.nip.input-prepend .add-on').text($(this).val());
            if($(this).val()!=""){
                $('#vies').attr('disabled',false);
                $('#vies2').attr('disabled',false);
                $('#memberStateCode').val($(this).val());
            }
            if($(this).val()=="PL"){
                $('#gus').attr('disabled',false);
            }else{
                $('#gus').attr('disabled',true);
            }
        }
    });

    $('#fnip').change(function(){
        $('#number').val($(this).val());
    });




    $('#gus').click(function(){
        var getUrl = window.location;
        if (getUrl.pathname.split('/').length < 4){
            var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
        }else{
            var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
        }
        $('#loading_animation').show();
        $.ajax({
            type: "POST",
            url: baseUrl + "/nip",
            //data: {nip:$('.nip input').val()},
            //dataType: "html",
            success: function (msg) {
                $('#loading_animation').hide();
                $('#testdialog').html(msg);
                $('#testdialog .nip').val($('#fnip').val());
                $('#testdialog').dialog();
                //d = document.createElement('div');
                //$(d).html(msg);
                //$(d).dialog();
                //$("#thanks").html(msg) //hide button and show thank you
                //$("#form-content").modal('hide'); //hide popup
            },
            error: function (msg) {
                $('#loading_animation').hide();
                $.sticky("Błąd wysyłania", {autoclose: false, position: "top-center", type: "st-error"});
                //console.log(msg);
            }
        });
    });

    $('#vies').click(function(){
        var getUrl = window.location;
        if (getUrl.pathname.split('/').length < 4){
            var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
        }else{
            var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
        }
        $('#loading_animation').show();
        $.ajax({
            type: "POST",
            url: baseUrl + "/nip",
            data: {countryCode: $('#kraj').val(),nip:$('.nip input').val(), vies:1},
            //dataType: "html",
            success: function (msg) {
                $('#loading_animation').hide();
                $('#testdialog').html(msg);
                $('#testdialog').dialog();
                //d = document.createElement('div');
                //$(d).html(msg);
                //$(d).dialog();
                //$("#thanks").html(msg) //hide button and show thank you
                //$("#form-content").modal('hide'); //hide popup
            },
            error: function (msg) {
                $('#loading_animation').hide();
                $.sticky("Błąd wysyłania", {autoclose: false, position: "top-center", type: "st-error"});
                console.log(msg);
            }
        });
    });

    var validator_c = $('.form_client').validate({
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
            zablokowany: {required: true},
            fname: {required: true},
            fshort_name: {required: true},
            //fnip: {required: true},
            adres: {required: true},
            post_code: {required: true},
            city: {required: true}
        },
        messages: {
            zablokowany: {required: "Pole wymagane"},
            fname: {required: "Pole wymagane"},
            fshort_name: {required: "Pole wymagane"},
            //fnip: {required: "Pole wymagane"},
            adres: {required: "Pole wymagane"},
            post_code: {required: "Pole wymagane"},
            city: {required: "Pole wymagane"}
        },
        invalidHandler: function (form, validator) {
            $.sticky("Popraw błędnie wypełnione pola w formularzu", {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });

    $('#drugi').validate({
        onkeyup: false,
        errorClass: 'error',
        validClass: 'valid',
        highlight: function (element) {

        },
        unhighlight: function (element) {

        },
        errorPlacement: function (error, element) {
            //$(element).closest('div').append(error);
            //console.log(error);
        },
        rules: {
            memberStateCode: {required: true},
            number: {
                required: true,
                minlength:8}
        },
        messages: {
            memberStateCode: {required: "Kod państwa wymagany"},
            number: {required: "NIP wymagany"}
        },
        invalidHandler: function (form, validator) {
            $.sticky("Aby otworzyć stronę VIES należy wybrać Państwo oraz podać numer NIP", {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });
});

