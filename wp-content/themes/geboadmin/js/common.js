$(document).ready(function(){
    $('input').each(function(){
        $(this).attr('autocomplete', 'off')
    });

    $(document).on('keyup keypress', 'form input[type="text"]', function(e) {
        if(e.which == 13) {
            e.preventDefault();
            return false;
        }
    });
});

function delete_link(e,link,dt){
    e.preventDefault();
    $('#loading_animation').show();

    $.ajax({
        type: 'POST',
        url: link.attr('href'),
        dataType: 'json',
        success: function (msg) {
            $('#loading_animation').hide();
            if (msg.status == 'success') {
                //console/.debug('delete_link dt',dt);
                //var row = link.closest("tr").get(0);
                //row.remove();
                dt.row( link.parents('tr') )
                    .remove()
                    .draw(false);

                //dt.fnDeleteRow(dt.fnGetPosition(row));
                $.sticky(msg.msg+msg.id, {autoclose: false, position: "top-center", type: "st-success"});
            } else if (msg.status == 'error'){
                $.sticky(msg.msg+' '+msg.id, {autoclose: false, position: "top-center", type: "st-error"});
            }
        },
        error: function (msg) {
            $('#loading_animation').hide();
            $.sticky('BŁĄD:'+msg.msg, {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });
    return false; // don't follow the link!
}

function disable_link(e,link,dt){
    e.preventDefault();
    $('#loading_animation').show();

    $.ajax({
        type: 'POST',
        url: link.attr('href'),
        dataType: 'json',
        success: function (msg) {
            $('#loading_animation').hide();
            if (msg.status == 'success') {
                //console/.debug('delete_link dt',dt);
                //var row = link.closest("tr").get(0);
                //row.remove();
                //dt.row( link.parents('tr') )
                link.parents('tr')
                    .addClass('disabled')
                    .find('.ajax-disable').each(function(){
                        $(this).remove();
                    });
                    //.draw(false);
                dt.ajax.reload( null, false );

                //dt.fnDeleteRow(dt.fnGetPosition(row));
                $.sticky(msg.msg+msg.id, {autoclose: false, position: "top-center", type: "st-success"});
            } else if (msg.status == 'error'){
                $.sticky(msg.msg+' '+msg.id, {autoclose: false, position: "top-center", type: "st-error"});
            }
        },
        error: function (msg) {
            $('#loading_animation').hide();
            $.sticky('BŁĄD:'+msg.msg, {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });
    return false; // don't follow the link!
}

function enable_link(e,link,dt){
    e.preventDefault();
    $('#loading_animation').show();

    $.ajax({
        type: 'POST',
        url: link.attr('href'),
        dataType: 'json',
        success: function (msg) {
            $('#loading_animation').hide();
            if (msg.status == 'success') {
                //console/.debug('delete_link dt',dt);
                //var row = link.closest("tr").get(0);
                //row.remove();
                //dt.row( link.parents('tr') )
                link.parents('tr')
                    .removeClass('disabled')
                    .find('.ajax-enable').each(function(){
                        $(this).remove();
                    });
                dt.ajax.reload( null, false );
                //.draw(false);

                //dt.fnDeleteRow(dt.fnGetPosition(row));
                $.sticky(msg.msg+msg.id, {autoclose: false, position: "top-center", type: "st-success"});
            } else if (msg.status == 'error'){
                $.sticky(msg.msg+' '+msg.id, {autoclose: false, position: "top-center", type: "st-error"});
            }
        },
        error: function (msg) {
            $('#loading_animation').hide();
            $.sticky('BŁĄD:'+msg.msg, {
                autoclose: false,
                position: "top-center",
                type: "st-error"
            });
        }
    });
    return false; // don't follow the link!
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}




