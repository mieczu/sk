$(document).ready(function(){

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
            date_applies: {required: true},
            typ: {required: true},
            fname: {required: true},
            netto: {
                required: true,
                number: true
            },
            vat: {
                required: true,
                number: true
            },
            brutto: {
                required: true,
                number: true
            },
            date_payment: {required: true}
        },
        messages: {
            date_applies: {required: "Pole wymagane"},
            typ: {required: "Pole wymagane"},
            fname: {required: "Pole wymagane"},
            netto: {
                required: "Pole wymagane",
                number: "Podaj poprawną wartość liczbową"
            },
            vat: {
                required: "Pole wymagane",
                number: "Podaj poprawną wartość liczbową"
            },
            brutto: {
                required: "Pole wymagane",
                number: "Podaj poprawną wartość liczbową"
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
        dateFormat: 'dd-mm-yy',
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
        dateFormat: 'dd-mm-yy'
    });

    $('#date_paid').datepicker( {
        dateFormat: 'dd-mm-yy'
    });




});
