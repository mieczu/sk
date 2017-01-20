
$(document).ready(function() {
    var getUrl = window.location;
    if (getUrl.pathname.split('/').length < 4) {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
    } else {
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/";
    }

    var dt_pozycje = $('#dt_pozycje').DataTable({
        "ajax": baseUrl + '/pozycje/?xhr=1',
        "order": [[0, "desc"]],
        "oLanguage": {
            "sEmptyTable": "The table is really empty now!"
        }
    }).on('draw.dt', function (a, settings, data) {
        $('.ajax-link').click(function (e) {
            delete_link(e, $(this), dt_pozycje);
        });
    });

    $('#addPoz').click(function(){
        $('#loading_animation').show();
        $.ajax({
            type: "POST",
            url: baseUrl + "pozycje/?action=new",
            data: {poz_name:$('#poz_name').val()},
            dataType: "json",
            success: function (msg) {
                $('#loading_animation').hide();
                console.debug(msg);
                console.debug(dt_pozycje);
                if (msg.status == 'success') {
                    dt_pozycje.row.add([
                        msg.id,
                        $('#poz_name').val(),
                        '<a href="' + baseUrl + 'pozycje?action=delete&id=' + msg.id + '"class="ajax-link" title="Usuń"><i class="splashy-remove"></i></a>'
                    ]).draw(false);

                    $.sticky("Dodano pozycję.", {position: "top-center", type: "st-success"});

                }else{
                    $.sticky(msg.msg, {position: "top-center", type: "st-error"});
                }
            },
            error: function () {
                $('#loading_animation').hide();
                $.sticky("Nie dodano poddostawcy.", {autoclose: false, position: "top-center", type: "st-error"});
            }
        });
    });
});