$(document).ready(function(){
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4){
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    }else{
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    $.datepicker.regional['pl'] = {
        closeText: 'Zamknij',
        currentText: 'Dziś',
        monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec', 'Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
        monthNamesShort: ['Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru'],
        dateFormat: 'dd/mm/yy'
    };

    $.datepicker.setDefaults($.datepicker.regional['pl']);

    $('#date_applies').datepicker( {
        changeMonth: true,
        changeYear: true,
        timepicker: false,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        onClose: function(dateText, inst) {
            $('#ui-datepicker-div').removeClass('hide-calendar');
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        },
        beforeShow: function(el, dp) {
            $('#ui-datepicker-div').toggleClass('hide-calendar', $(el).is('[data-calendar="false"]'));
            // alert($(el).is('[data-calendar="false"]'));
            // alert($(this).is('[data-calendar="false"]'));
            // alert($('#date_applies').is('[data-calendar="false"]'));
        }
    });

    $('#date_payment').datepicker( {
        dateFormat: 'yy-mm-dd'
    });

    $('#date_paid').datepicker( {
        dateFormat: 'yy-mm-dd'
    });

    $.validator.addMethod('isDT', function (value) {
        var re = new RegExp("^([1-9]{1}[0-9]{3}-[0-9]{2}-[0-9]{2})$");
        if (re.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "To nie jest poprawna data");

    $.validator.addMethod('kosztBrutto', function (value) {
        var netto = parseFloat($('#netto').val().replace(',', '.'));
        var vat = parseFloat($('#vat').val().replace(',', '.'));
        // console.debug("n:"+netto);
        // console.debug("v:"+vat);
        // var brutto = netto+vat;
console.debug(brutto.toFixed(2));
        if ((brutto.toFixed(2))==value.replace(',', '.')) {
            return true;
        } else {
            return false;
        }
    }, "Niepoprawna wartość brutto");

    jQuery.validator.addMethod("mynumber", function (value, element) {
        return this.optional(element) || /^(\d+|\d+,|\d+.\d{1,4})$/.test(value);
    }, "Podaj poprawną liczbę");

    var validator = $('.form_koszty').validate({
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
            date_applies: {
                required: true,
                isDT: true
            },
            typ: {required: true},
            fname: {required: true},
            netto: {
                required: true,
                mynumber: true
            },
            vat: {
                required: true,
                mynumber: true
            },
            brutto: {
                required: true,
                mynumber: true,
                kosztBrutto: true
            },
            date_payment: {
                required: true,
                isDT: true
            }
        },
        messages: {
            date_applies: {required: "Pole wymagane"},
            typ: {required: "Pole wymagane"},
            fname: {required: "Pole wymagane"},
            netto: {
                required: "Pole wymagane",
                mynumber: "Podaj poprawną wartość liczbową"
            },
            vat: {
                required: "Pole wymagane",
                mynumber: "Podaj poprawną wartość liczbową"
            },
            brutto: {
                required: "Pole wymagane",
                mynumber: "Podaj poprawną wartość liczbową"
            },
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

    var dt_koszty = $('#dt_koszty').DataTable({
        "ajax": baseUrl + '/koszty/?xhr=1',
        "order": [[ 0, "desc" ]],
        "oLanguage": {
            "sEmptyTable": "Brak dodanych kosztów"
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "issuer" },
            { "data": "number" },
            { "data": "typ" },
            { "data": "netto" },
            { "data": "vat" },
            { "data": "brutto" },
            { "data": "date_applies" },
            { "data": "date_payment" },
            { "data": "date_paid" },
            { "data": "buttons" }
        ],
        "createdRow": function (row, data, dataIndex) {
            var now = new Date();
            var date = new Date(data["date_payment"]);
            var paid = new Date(data["date_paid"]);
            // alert(data['date_payment']);
            if (paid.getTime()) {
                $(row).addClass('paid');
            } else if (now.getTime() == date.getTime()) {
                $(row).addClass('important');
            } else if (now.getTime() > date.getTime()) {
                $(row).addClass('important');
            }
        },
        "initComplete": function (settings, json) {
            $('.ajax-link').unbind();
            $('.ajax-link').click(function (e) {
                e.preventDefault();
                var link = $(this);

                smoke.confirm('Potwierdź usunięcie?', function (a) {
                    if (a) {
                        delete_link(e, link, dt_koszty);
                    } else {
                    }
                }, {ok: "Skasuj", cancel: "Anuluj"})
            });
        }
        // ,
        // "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
        //     $('td:eq(2)', nRow).html('<a href="view.php?comic=' + aData[1] + '">' +
        //         aData[1] + '</a>');
        //     return nRow;
        // },
    }).on( 'draw.dt', function (a, settings, data) {
        $('.ajax-link').unbind();
        $('.ajax-link').click(function (e) {
            e.preventDefault();
            var link = $(this);

            smoke.confirm('Potwierdź usunięcie?', function (a) {
                if (a) {
                    delete_link(e, link, dt_koszty);
                } else {
                }
            }, {ok: "Skasuj", cancel: "Anuluj"})
        });
    });

    

});
