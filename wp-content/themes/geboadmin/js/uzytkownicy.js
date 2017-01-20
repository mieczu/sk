$('#addUser').validate({
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
        'first-name': {required: true},
        'last-name': {required: true},
        email: {
            required: true,
            email: true
        },
        role: {required: true},
        password: {
            required: true,
            minlength: 8
        },
        password_confirm: {
            required: true,
            minlength: 8,
            equalTo: "#password"
        }
    },
    messages: {
        'first-name': {required: "Pole wymagane"},
        'last-name': {required: "Pole wymagane"},
        email: {
            required: "Pole wymagane",
            email: "To nie jest poprawny adres"
        },
        role: {required: "Pole wymagane"},
        password: {
            required: "Pole wymagane",
            minlength: "Hasło powinno mieć conajmniej 8 znaków"
        },
        password_confirm: {
            required: "Pole wymagane",
            minlength: "Hasło powinno mieć conajmniej 8 znaków ",
            equalTo: "Wpisane hasła różnią się"
        }
    },
    invalidHandler: function (form, validator) {
        $.sticky("Popraw błędnie wypełnione pola w formularzu", {
            position: "top-center",
            type: "st-error"
        });
    }
});

$(document).ready(function () {
    var dtUser = $('#dt_gal').dataTable({
        // "sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        // "sPaginationType": "bootstrap",
        "aaSorting": [[ 6, "asc" ]],
        "columnDefs": [
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            }
        ]
        //"aoColumns": [
        //	{ "bSortable": false },
        //	{ "bSortable": false },
        //	{ "sType": "string" },
        //	{ "sType": "formatted-num" },
        //	{ "sType": "eu_date" },
        //	{ "bSortable": false }
        //]
    });
    $('.dt_actions').html($('.dt_gal_actions').html());

    $('.ajax-delete').click(function () {
        $('#loading_animation').show();
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'),
            dataType: 'json',
            success: function (msg) {
                $('#loading_animation').hide();
                if (msg.status == 'success') {
                    var row = $(this).closest("tr").get(0);
                    dtUser.fnDeleteRow(dtUser.fnGetPosition(row));
                    $.sticky('Usunięto użytkownika: '+msg.id, {autoclose: false, position: "top-center", type: "st-success"});
                    //window.location.href = 'http://'+window.location.host+window.location.pathname;

                } else if (msg.status == 'error'){
                    $.sticky('Użytkownik nie został usunięty: '+msg.id, {autoclose: false, position: "top-center", type: "st-error"});
                }
            },
            error: function () {
                $('#loading_animation').hide();
                $.sticky('BŁĄD: użytkownik nie został usunięty', {
                    autoclose: false,
                    position: "top-center",
                    type: "st-error"
                });
            }
        });

        //$("html").removeClass("js")
        return false; // don't follow the link!
    });


});