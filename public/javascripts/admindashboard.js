$(document).ready(function() {
    // Set the total amount of meals in the administration interface
    $('body.administratie select#count').change(function(){
      $(this).parents('form').submit();
    });

    $(document).on('click', '.confirmation-needed', confirm_intent);

    $(document).on('click', '.destroy-registration', remove_registration);

    $(document).on('click', '.new_registration input[type="submit"]', add_registration);
    $(document).on('keyup', '.new_registration input[type="text"]', add_registration_if_enter);

    // Interactive tables for administration
    hide_subtables();
    $('.expander').click(toggle_subtable);
});

/**
 * Confirms the intent of the user
 */
function confirm_intent() {
    return confirm('Are you sure?');
}

function add_registration_if_enter(data) {
    // If enter is pressed
    if (data.which == 13) {
        // Trigger the submit-function
        var row = $(this).parents('.new_registration');
        $('input[type="submit"]',row).click();
    }
}

/**
 * Submits a registration to the server
 */
function add_registration()
{
    // Get the values, ignoring whitespace
    var form = $(this).parents('.new_registration');
    var meal_id = $(this).parents('tbody').attr('data-id');
    var name = $('input[name="name"]',form).val().trim();
    var handicap = $('input[name="handicap"]',form).val().trim();

    if (name !== '') {
        // Update the server
        $.post('/administratie/aanmelden',{
                meal_id: meal_id,
                name: name,
                handicap: handicap
            },
            function(new_row){
                // Update meal
                $('tbody[data-id="'+meal_id+'"]').replaceWith(new_row);
                // Re-open the list of names
                $('.expander', 'tbody[data-id="'+meal_id+'"]').click();
            },'html');
    }
}

/**
 * Removes a registration from the server
 */
function remove_registration()
{
    // Get the value, ignoring whitespace
    var meal = $(this).parents('.meal');
    var registration = $(this).parents('.registration');
    var meal_id = meal.attr('data-id');
    var name = $('.name',registration).html();

    if (confirm('Weet je zeker dat je '+name+' wilt uitschrijven?')) {
        $.post($(this).attr('href'), null,
            function(result){
                if (result == 'success') {
                    $(registration).remove();
                }
                else {
                    alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
                }
            });
    }

    // Stop default event (follow link)
    return false;
}

function hide_subtables() {
    $('.registration, .new_registration').hide();
}

function toggle_subtable() {
    // Find the subtable
    var meal = $(this).parents('tbody');

    // Hide the rows
    $('.registration, .new_registration', meal).toggle();

    // Toggle arrow
    if ($(this).attr('src') == '/images/arrow-right.png') {
        $(this).attr('src', '/images/arrow-down.png');
    }
    else {
        $(this).attr('src', '/images/arrow-right.png');
    }

}
