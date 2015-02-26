var disabled_days = null;

$(document).ready(function() {
    // Only execute loading days on the page that's needed
    if ($('body.administratie.nieuwe_maaltijd, body.administratie.bewerk').size() > 0) {
        // Load disabled days
        get_disabled_days();

        $('.datepicker').datepicker({
            minDate: 0,
            showWeek: true,
            firstDay: 1,
            dayNames: ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag'],
            dayNamesMin: ['zo','ma','di','wo','do','vr','za'],
            dateFormat: 'yy-mm-dd',
            beforeShowDay: check_free_date
        });
    }

    // Interactive tables for administration
    hide_subtables();
    $('.expander').click(toggle_subtable);
});

function add_registration_if_enter(data) {
    // If enter is pressed
    if (data.which == 13) {
        // Trigger the submit-function
        var row = $(this).parents('.new_registration');
        $('input[type="submit"]',row).click();
    }
}

/**
 * Retrieves an array of all disabled dates from the server
 * @return void
 */
function get_disabled_days() {
    // Exclude current day
    var meal_id = null;
    if ($('form').size() > 0) {
        meal_id = $('form').attr('data-id');
    }

    $.ajax({
        url: '/administratie/gevulde_dagen',
        data: {
            meal_id: meal_id
        },
        success: function(result) {
            disabled_days = result
        },
        dataType: 'json',
        async: false
    });
}

/**
 * Checks whether a specific date is still free
 * @param Date date
 * @return array[boolean]
 */
function check_free_date(date) {
    var date_string = date.format('yyyy-mm-dd');
    if ($.inArray(date_string, disabled_days) > -1) {
        return [false];
    }
    else {
        return [true];
    }
}
