$(document).ready(function () {
    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = new Date($('#minZlec').val()+' 00:00:00');
            var max = new Date($('#maxZlec').val()+' 23:59:59');
            var date = new Date(data[8]);

// console.debug('min',min);
//             console.debug('max',max);
//             console.debug('date',date);

            if (min.getTime() && max.getTime()) {
                if (min.getTime() > date.getTime()) {
                    return false
                }
                if (max.getTime() < date.getTime()) {
                    return false
                }
                return true;
            }else if (min.getTime()){
                if (min.getTime() > date.getTime()) {
                    return false
                }
                return true;
            }else if (max.getTime()) {
                if (max.getTime() < date.getTime()) {
                    return false
                }
                return true;
            }
            return true;
        }
    );

    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }


    //console.debug(getUrl.pathname.split('/').length
    //);
    //alert(bas
    // eUrl);
    //console.debug(getUrl);

    /*
     * Główna tabela ze zleceniami
     */
    var dt_zlecenia = $('#dt_zlecenia').DataTable({
        "ajax": baseUrl + 'zlecenia/?xhr=1',
        "order": [[0, "desc"]],
        "stateSave": true,
        "columnDefs": [
            {className: "display_none", "targets": [10, 11, 12, 13, 14]}
        ],

        // "columns": [
        //     { "visibility": false },
        //     { "visibile": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //     { "searchable": false },
        //
        // ],
        // "columnDefs": [
        //     {
        //         "targets": [ 10,11,12,13 ],
        //         "visible": false,
        //         "searchable": false
        //     }
        // ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            //console.debug(aData.length);
            //console.debug(aData);
            //console.debug(aData[10]);
            //var css = aData[aData.length - 1];
            if (aData[10] == 1) {
                $(nRow).addClass('disabled');
            }
            else {
                //$(nRow).addClass('gradeN');
            }
        },
        "initComplete": function (settings, json) {
            $('#dt_zlecenia .ajax-link').unbind();
            $('#dt_zlecenia .ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);

                smoke.confirm('Potwierdź usunięcie?', function (a) {
                    if (a) {
                        delete_link(e, link, dt_zlecenia);
                    } else {
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
            $('#dt_zlecenia .ajax-disable').unbind();
            $('#dt_zlecenia .ajax-disable').click(function (e) {
                e.preventDefault();
                var link = $(this);

                smoke.confirm('Potwierdź zablokowanie?', function (a) {
                    if (a) {
                        disable_link(e, link, dt_zlecenia);
                    } else {
                    }
                }, {ok: "Zablokuj", cancel: "Anuluj"})
            });
            $('#dt_zlecenia .ajax-enable').unbind();
            $('#dt_zlecenia .ajax-enable').click(function (e) {
                e.preventDefault();
                var link = $(this);

                smoke.confirm('Potwierdź odblokowanie?', function (a) {
                    if (a) {
                        enable_link(e, link, dt_zlecenia);
                    } else {
                    }
                }, {ok: "Odblokuj", cancel: "Anuluj"})
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_zlecenia .ajax-link').unbind();
        $('#dt_zlecenia .ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);
            smoke.confirm('Potwierdź usunięcie?', function (a) {
                if (a) {
                    delete_link(e, link, dt_zlecenia);
                } else {
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})

        });
        $('#dt_zlecenia .ajax-disable').unbind();
        $('#dt_zlecenia .ajax-disable').click(function (e) {
            e.preventDefault();
            var link = $(this);

            smoke.confirm('Potwierdź zablokowanie?', function (a) {
                if (a) {
                    disable_link(e, link, dt_zlecenia);
                } else {
                }
            }, {ok: "Zablokuj", cancel: "Anuluj"})
        });
        $('#dt_zlecenia .ajax-enable').unbind();
        $('#dt_zlecenia .ajax-enable').click(function (e) {
            e.preventDefault();
            var link = $(this);

            smoke.confirm('Potwierdź odblokowanie?', function (a) {
                if (a) {
                    enable_link(e, link, dt_zlecenia);
                } else {
                }
            }, {ok: "Odblokuj", cancel: "Anuluj"})
        });
    });

    $('#minZlec, #maxZlec').keyup(function () {
        dt_zlecenia.draw();
    }).change(function () {
        dt_zlecenia.draw();
    });

    var table = window.table = $('#dt_podwykonawcy').DataTable({
        "order": [[0, "desc"]],
        "stateSave": true,
        "oLanguage": {
            "sEmptyTable": "Brak podwykonawców"
        },
        "initComplete": function (settings, json) {
            $('#dt_podwykonawcy .ajax-link').unbind();
            $('#dt_podwykonawcy .ajax-link').click(function (e) {
                //console.debug('zzzz', table);
                delete_link(e, $(this), table);
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_podwykonawcy .ajax-link').unbind();
        $('#dt_podwykonawcy .ajax-link').click(function (e) {
            delete_link(e, $(this), table);
        });
    });

    var dt_faktury = $('#dt_faktury').DataTable({
        "ajax": baseUrl + "faktury/?action=get&id_order=" + $('#id_order').val(),
        "order": [[0, "desc"]],
        "stateSave": true,
        "oLanguage": {
            "sEmptyTable": "Brak faktur"
        },
        "initComplete": function (settings, json) {
            $('#dt_faktury .ajax-link').click(function (e) {
                delete_link(e, $(this), dt_faktury);
            });
        }
    }).on('draw.dt', function (a, settings, data) {
        $('#dt_faktury .ajax-link').unbind();
        $('#dt_faktury .ajax-link').click(function (e) {
            delete_link(e, $(this), dt_faktury);
        });
    });

    var dt_noty = $('#dt_noty').DataTable({
        "ajax": baseUrl + "noty/?action=get&id_order=" + $('#id_order').val(),
        "order": [[0, "desc"]],
        "stateSave": true,
        "initComplete": function (settings, json) {
            $('#dt_noty .ajax-link').unbind();
            $('#dt_noty .ajax-link').click(function (e) {
                delete_link(e, $(this), dt_noty);
            });
        }
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

    $.validator.addMethod('isDT', function (value) {
        var re = new RegExp("^([0-9]{4}-[0-9]{2}-[0-9]{2})$");
        if (re.test(value) || value.length == 0) {
            return true;
        } else {
            return false;
        }
    }, "To nie jest poprawna data");

    //$("#addZlecenie #nadawca").combobox();
    //$("#addZlecenie #odbiorca").combobox();
    //$("#addZlecenie #platnik").combobox();

    $("#nadawca").autocomplete({
            minLength: 2,
            source: baseUrl + "klienci/?action=autocomplete",
            focus: function (event, ui) {
                console.debug(ui);
                //$(this).val( ui.item.name );
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.short_name);
                $("#idnadawca").val(ui.item.id);

                return false;
            },
            change: function (event, ui) {
                // console.debug('change1');
                // console.debug(ui);
                $.ajax({
                    type: "POST",
                    url: baseUrl + "klienci/?action=by_name",
                    data: {'term': $('#nadawca').val()},
                    dataType: 'json',
                    success: function (msg) {
                        console.debug(msg);
                        if (msg == null) {
                            $('#idnadawca').val(0);
                        }

                        if (msg.id > 0) {
                            $('#idnadawca').val(msg.id);
                        } else {
                            $('#idnadawca').val(0);
                        }
                    },
                    error: function () {
                        $('#idnadawca').val(0);
                    }
                });
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.short_name + "</a>")
            .appendTo(ul);
    };

    $("#odbiorca").autocomplete({
            minLength: 2,
            source: baseUrl + "klienci/?action=autocomplete",
            focus: function (event, ui) {
                //console.log(ui);
                //$(this).val( ui.item.name );
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.short_name);
                $("#idodbiorca").val(ui.item.id);
                return false;
            },
            change: function (event, ui) {
                // console.debug('change1');
                // console.debug(ui);
                $.ajax({
                    type: "POST",
                    url: baseUrl + "klienci/?action=by_name",
                    data: {'term': $('#odbiorca').val()},
                    dataType: 'json',
                    success: function (msg) {
                        console.debug(msg);
                        if (msg == null) {
                            $('#idodbiorca').val(0);
                        }

                        if (msg.id > 0) {
                            $('#idodbiorca').val(msg.id);
                        } else {
                            $('#idodbiorca').val(0);
                        }
                    },
                    error: function () {
                        $('#idodbiorca').val(0);
                    }
                });
            }

        })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.short_name + "</a>")
            .appendTo(ul);
    };

    $('#add_zlecenie').prop('disabled', true);

    $("#platnik").autocomplete({
            minLength: 2,
            source: baseUrl + "klienci/?action=autocomplete",
            focus: function (event, ui) {
                console.log(ui);
                //$(this).val( ui.item.name );
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.short_name);
                $("#idplatnik").val(ui.item.id);
                $('#loading_animation').show();


                var input = $(this)
                $.ajax({
                    type: "POST",
                    url: baseUrl + "/klienci?action=get_limit&id=" + ui.item.id,
                    //data: {captcha: $('#cap').val()},//$('#checknip .nip').val()},
                    dataType: "json",
                    success: function (msg) {
                        $('#loading_animation').hide();

                        //var obj = JSON.parse('{"invoice":{"id":"1374","id_user":"26","edit_id_user":null,"id_order":"638","id_subcontractor":null,"subcontractor":null,"id_subcontractor_to_order":null,"id_parent":null,"id_client":"12","typ":"Sprzeda\u017c","numer":"102\/07\/2016","hnumer":"102","value":"19972000","vat":"805000","vat_currency":"pln","usd":"41180","eur":"0","currency":"pln","paid":null,"date_add":"2016-07-21 07:40:53","date_mod":"2016-07-21 07:40:53","date_payment":"2016-08-21","date_received":"2016-07-20","note":"1 usd=4,1180 pln kurs sprzeda\u017cy banku BZ WBK z dn.20.07.16","date_paid":null,"lang":"0","is_paid":null},"nota":{"id":"35","id_order":"240","id_client":"12","id_user":"26","number":"02\/05\/2016","hnumber":"2","typ":"Ksi\u0119gowa","content":"","lang":"0","value":"138690000","usd":"0","eur":"0","duty":"28250000","tax":"110440000","currency":"pln","sad":"443020\/00\/129377\/2016","sad_date":"2016-05-04","note":"zap\u0142acone 05.05.16","date_add":"2016-05-04 15:23:10","date_mod":"2016-05-06 12:00:54","date_paid":null,"date_paid2":null,"date_payment":null,"disabled":"0","is_paid":null},"invoices":11426.735,"notas":0}');
                        $("#platnik_limit").html('<p>Aktualny limit :<b>' + msg.suma + ' PLN</b></p><p>Maksymalny limit :<b>' + msg.limit + ' PLN</b></p>');
                        if (parseFloat(msg.suma) > parseFloat(msg.limit)) {

                            $('#add_zlecenie').prop('disabled', true);
                        } else {

                            $('#add_zlecenie').prop('disabled', false);
                        }
                    },
                    error: function (msg) {
                        $('#loading_animation').hide();
                        $('#add_zlecenie').prop('disabled', true);
                        $("#platnik_limit").html('<h3>Błąd pobierania limitów</h3>');
                    }
                });

                return false;
            },
            change: function (event, ui) {
                if (ui.item == null || ui.item == undefined) {
                    $("#platnik").val("");
                    //$("#platnik").attr("disabled", false);
                } else {
                    //$("#platnik").attr("disabled", true);
                }
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.short_name + "</a>")
            .appendTo(ul);
    };

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
            transport: {required: true},
            typ: {required: true},
            destination: {required: true},
            orgin: {required: true},
            eta: {
                isDT: true
            }
        },
        messages: {
            transport: {required: "Pole wymagane"},
            typ: {required: "Pole wymagane"},
            destination: {required: "Pole wymagane"},
            orgin: {required: "Pole wymagane"},
            eta: {
                idDT: "To nie jest poprawna data "
            }
        },
        invalidHandler: function (form, validator) {
            $.sticky("Popraw błędnie wypełnione pola w formularzu", {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });


    $('.form_order').submit(function (e) {
        if ($('#nadawca').val() == 0) {
            e.preventDefault();
            validator.showErrors({
                "nadawca": "Musisz podać nadawcę!"
            });
        }

        if ($('#odbiorca').val() == 0) {
            e.preventDefault();
            validator.showErrors({
                "odbiorca": "Musisz podać odbiorcę!"
            });
        }

        if ($('#platnik').val() == 0) {
            e.preventDefault();
            validator.showErrors({
                "platnik": "Musisz podać platnika!"
            });
        }
    });


    jQuery.datetimepicker.setLocale('pl');

    $('#eta').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#etd').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#fin').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#fout').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });
    $('#minZlec').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    $('#maxZlec').datetimepicker({
        format: 'Y-m-d',
        timepicker: false
    });
    $('#date_execute').datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false
    });

    $('#awb').mask('***-**** ****');


    $('input[name=transport]').on('change', function () {
        if ($(this).val() == 'Drogowy') {


            $('#morski').hide();
            $('input[name=awb]').val('');
            $('input[name=hawb]').val('');
            $('#lotniczy').hide();
            $('input[name=bl]').val('');
            $('input[name=hbl]').val('');
        }

        if ($(this).val() == 'Lotniczy') {
            $('#morski').hide();
            $('input[name=awb]').val('');
            $('input[name=hawb]').val('');
            $('#lotniczy').show();
        }

        if ($(this).val() == 'Morski') {
            $('#morski').show();
            $('#lotniczy').hide();
            $('input[name=bl]').val('');
            $('input[name=hbl]').val('');
        }
    });

    $("#addClientForm form").submit(function () {
        alert('aaaa');
    });

    $("input#submit").click(function (event) {
        //event.preventDefault();

        console.log($('#addClientForm form').serializeArray());
        $.ajax({
            type: "POST",
            url: baseUrl + "klienci/?action=new&xhr=1",
            data: $('#ajaxAddClientForm').serialize(),
            success: function (msg) {
                $("#thanks").html(msg) //hide button and show thank you
                $("#form-content").modal('hide'); //hide popup
            },
            error: function () {
                $.sticky("Błąd wysyłania", {autoclose: false, position: "top-center", type: "st-error"});
            }
        });
    });

    $('#addFaktura').submit(function (e) {
        e.preventDefault();
        var formSubmit = $(this);
        var data = table.$('input, select').serialize();
        $.ajax({
            type: "POST",
            url: baseUrl + "podwykonawcy/?action=save_sub",
            data: data,
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                console.log(msg.status);
                if (msg.status == 'error') {
                    $.sticky("Błąd: nie można zapisać podwykonawcy id:" + msg.id, {
                        autoclose: false,
                        position: "top-center",
                        type: "st-error"
                    });
                } else if (msg.status == 'success') {
                    formSubmit.unbind().submit();
                } else {
                    $.sticky("Nieoczekiwany błąd", {autoclose: false, position: "top-center", type: "st-error"});
                }
                //alert(msg);
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
    });


    $('#subSave').click(function () {
        $('#loading_animation').show();
        var data = table.$('input, select').serialize();
        $.ajax({
            type: "POST",
            url: baseUrl + "podwykonawcy/?action=save_sub",
            data: data,
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                console.log(msg.status);
                if (msg.status == 'error') {
                    $.sticky("Błąd: nie można zapisać podwykonawcy id:" + msg.id, {
                        autoclose: false,
                        position: "top-center",
                        type: "st-error"
                    });
                } else if (msg.status == 'success') {
                    $.sticky("Zapisano podwykonawców", {autoclose: false, position: "top-center", type: "st-success"});
                } else {
                    $.sticky("Nieoczekiwany błąd", {autoclose: false, position: "top-center", type: "st-error"});
                }
                //alert(msg);
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

    $('#subAdd').click(function () {
        $('#loading_animation').show();
        $.ajax({
            type: "POST",
            url: baseUrl + "podwykonawcy/?action=new_sub",
            data: {id_order: $('#id_order').val()},
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                console.debug(msg);
                console.debug(table);
                if (msg.status == 'success') {
                    table.row.add([
                        '<input class="subc" style="width: 150px;" id="row[' + msg.id + '][subc]" name="row[' + msg.id + '][subc]" value="" type="text"/>',
                        '<input class="name" style="width: 350px;" id="row[' + msg.id + '][name]" name="row[' + msg.id + '][name]" value="" type="text">',
                        '<input class="value" style="width: 100px;" id="row[' + msg.id + '][value]" name="row[' + msg.id + '][value]" value="" type="text">',
                        '<select class="wal" style="width: 75px;" id="row[' + msg.id + '][wal]" name="row[' + msg.id + '][wal]">' +
                        '<option value="eur">EURO</option>' +
                        '<option value="usd">USD</option>' +
                        '<option value="pln" selected="selected">PLN</option>' +
                        '</select>',
                        '<input class="exch" style="width: 40px;" id="row[' + msg.id + '][exch]" name="row[' + msg.id + '][exch]" value="1" type="text">' +
                        '<select class="rok" style="width: 60px;" id="row[' + msg.id + '][rok]" name="row[' + msg.id + '][rok]">' +
                        '<option></option>' +
                        '<option value="2016">2016</option>' +
                        '<option value="2015">2015</option>' +
                        '</select>' +
                        '<select class="tabela" style="width: 75px;" id="row[' + msg.id + '][tabela]" name="row[' + msg.id + '][tabela]">' +
                        '<option></option>' +
                        '</select>',
                        '<input class="id_sub" id="row[' + msg.id + '][id_sub]" name="row[' + msg.id + '][id_sub]" value="' + msg.id + '" type="hidden">' +
                        '<input class="id" id="row[' + msg.id + '][id]" name="row[' + msg.id + '][id]" value="" type="hidden">' +
                        '<input class="id_order" id="row[' + msg.id + '][id_order]" name="row[' + msg.id + '][id_order]" value="' + $('.id_order').val() + '" type="hidden">' +
                        '<form id="addFaktura" name="addFaktura" class="form_faktura" method="POST" action="/faktury?action=add">' +
                        '<input class="id_subcontractor" name="id_subcontractor_to_order" value="' + msg.id + '" type="hidden">' +
                        '<input class="id_order" name="id_order" value="' + $('#id_order').val() + '" type="hidden">' +
                        '<a href="' + baseUrl + 'podwykonawcy?action=delete_sub&id=' + msg.id + '"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>' +
                        '<button class="btn btn-gebo" type="submit">Dodaj fakturę</button>' +
                        '</form>'
                    ]).draw(false);

                    $.sticky("Dodano poddostawcę.", {position: "top-center", type: "st-success"});

                    $('.subc').each(function () {
                        addAutocomplete2($(this));
                    });

                    $('.name').each(function () {
                        addAutocompletePoz($(this));
                    });

                    $('.rok').unbind();
                    $('.rok').change(function () {
                        rokChange($(this));
                    });

                    $('.tabela').unbind();
                    $('.tabela').change(function () {
                        tabelaChange($(this));
                    });

                    $('#dt_podwykonawcy .ajax-link').unbind();
                    $('#dt_podwykonawcy .ajax-link').click(function (e) {
                        delete_link(e, $(this), table);
                    });

                    $('#dt_faktury .ajax-link').unbind();
                    $('#dt_faktury .ajax-link').click(function (e) {
                        e.preventDefault();
                        var link = $(this);

                        smoke.confirm('Potwierdź usunięcie?', function (a) {
                            if (a) {
                                // smoke.alert('"yeah it is" pressed', {ok:"close"});
                                delete_link(e, link, dt_faktury);
                            } else {
                                //smoke.alert('"no way" pressed', {ok:"close"});
                            }
                        }, {ok: "Skasuj", cancel: "Anuluj"});

                    });

                    $('#dt_zlecenia .ajax-link').unbind();
                    $('#dt_zlecenia .ajax-link').click(function (e) {
                        delete_link(e, $(this), dt_zlecenia);
                    });

                    //for(i=date('Y');i>=2015;i--){
                    //    document.write('<option value="'+i+'"'+(i==date('Y')?' selected="selected"':'')+'>'+i+'</option>');
                    //}
                }
            },
            error: function () {
                $('#loading_animation').hide();
                $.sticky("Nie dodano poddostawcy.", {autoclose: false, position: "top-center", type: "st-error"});
            }
        });

        //alert($('#dt_podwykonawcy tr').attr('class'));
        //$('#dt_podwykonawcy tbody').prepend('<tr><td>TigerNixon</td>' +
        //'<td><input id="row-9-age" name="row-9-age" value="61" type="text"></td>' +
        //'<td><input id="row-9-position" name="row-9-position" value="System Architect" type="text"></td>' +
        //'<td><select size="1" id="row-9-office" name="row-9-office">' +
        //'<option value="Edinburgh" selected="selected">Edinburgh</option>' +
        //'<option value="London">London</option>' +
        //'<option value="New York">New York</option>' +
        //'<option value="San Francisco">San Francisco</option>' +
        //'<option value="Tokyo">Tokyo</option>' +
        //'</select></td></tr>');
    });
    //table = $('#dt_podwykonawcy').DataTable({});

    $('.rok').change(function () {
        rokChange($(this));
    });

    $('.tabela').change(function () {
        tabelaChange($(this));
    });

    $('.subc').each(function () {
        addAutocomplete2($(this));
    });

    $('.name').each(function () {
        addAutocompletePoz($(this));
    });

    var icoterms_source = [
        "EXW",
        "FCA",
        "FOB",
        "CPT",
        "CIP",
        "CIF",
        "DAP",
        "DAT",
        "DDP"
    ];

    $('#icoterms').autocomplete({
        minLength: 0,
        source: icoterms_source,
        focus: function (event, ui) {
            return false;
        },
        select: function (event, ui) {
            //console.debug(ui);
            $(this).val(ui.item.value);

            return false;
        }
    }).focus(function () {
        console.debug('aaavvva');
        $(this).autocomplete("search", "");
    }).autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.value + "</a>")
            .appendTo(ul);
    };

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

function rokChange(element) {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    $('#loading_animation').show();
    var tr = element.closest('tr');
    var tb = tr.find('.tabela');
    tb.find('option:not(:first)').remove();
    $.ajax({
        type: "POST",
        url: baseUrl + "nbp/",
        data: {rok: element.val()},
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

function addAutocompletePoz(element) {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    element.autocomplete({
            minLength: 2,
            source: baseUrl + "/pozycje/?action=autocomplete",
            focus: function (event, ui) {
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.name);
                var tr = $(this).closest('tr');
                tr.find('.id').val(ui.item.id);

                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.name + "</a>")
            .appendTo(ul);
    };
}

function addAutocomplete2(element) {
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
                console.log(ui);
                //$(this).val( ui.item.name );
                return false;
            },
            select: function (event, ui) {
                $(this).val(ui.item.name);
                var tr = $(this).closest('tr');
                tr.find('.id').val(ui.item.id);
                //$( "#project" ).val( ui.item.label );
                //$( "#project-id" ).val( ui.item.value );
                //$( "#project-description" ).html( ui.item.desc );
                //$( "#project-icon" ).attr( "src", "images/" + ui.item.icon );

                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

        return $("<li>")
            .append("<a>" + item.name + "</a>")
            .appendTo(ul);
    };
}
