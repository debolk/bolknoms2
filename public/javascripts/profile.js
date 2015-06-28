$(document).ready(function(){

    // Set diet dialog
    $('#set_profile_handicap').on('click', update_handicap);
});

/**
 * Update the handicap setting of a user on the server
 */
function update_handicap(event)
{
    event.preventDefault();

    var handicap = $('#handicap').data('handicap');
    var handicap = prompt('Specificeer je dieetwensen zo exact en duidelijk mogelijk:', handicap);

    // No-op on cancel
    if (handicap == null) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: '/handicap',
        contentType: 'application/json',
        dataType: 'application/json',
        data: JSON.stringify({handicap: handicap}),
        success: function() {
            $('#handicap').data('handicap', handicap);

            if (handicap == '') {
                $('#handicap').html('Geen dieet ingesteld').addClass('no_diet');
            }
            else {
                $('#handicap').html('&ldquo;' + handicap + '&rdquo;').removeClass('no_diet');
            }
        },
        error: function(error){
            var error = JSON.parse(error.response);
            show_notification('error', '<strong>Fout:</strong> ' + error.error_details + '<br><br> Technische details: ' + error.error, $('#handicap').parents('.profile'));
        },
    });
}
