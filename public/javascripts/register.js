$(document).ready(function(){

    // If we are currently anonymous, hide the meals
    if ($('.user.noone').length > 0) {
        $('.meal').hide();
    }

    // Click handler for anonymous subscription
    $('.proceed_anonymous').on('click', function(event){
        event.preventDefault();

        $('.anonymous .method').hide(200);
        $('.anonymous form').show(200);
        $('.meal').show(200);
    });

    // Click handler and submission process for buttons
    $('.meal button').on('click', function(event){

        event.preventDefault();

        // Set button to working state
        var button = $(this);

        // Choose appropriate submission process
        if ($('.user.someone').size() == 1) {
            if (button.hasClass('unregistered')) {
                register(button);
            }
            else {
                deregister(button);
            }
        }
        else {
            if (button.hasClass('unregistered')) {
                registerNonUser(button);
            }
        }

    });
});

function register(button)
{
    set_button_state(button, 'busy');

    // Send AJAX-call to register for meal
    $.ajax({
        type: 'POST',
        url: '/aanmelden',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
           meal_id: button.data('id')
        }),
        success: function() {
            set_button_state(button, 'registered');
        },
        error: fatal_error,
    });
}

function deregister(button)
{
    set_button_state(button, 'busy');

    // Send AJAX-call to register for meal
    $.ajax({
        type: 'POST',
        url: '/afmelden',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
           meal_id: button.data('id')
        }),
        success: function() {
            set_button_state(button, 'unregistered');
        },
        error: fatal_error,
    });
}

function registerNonUser(button)
{
    set_button_state(button, 'busy');

    // Check form fields
    if ($('#name').val() == '' || $('#email').val() == '') {
        show_notification('error', '<strong>Fout:</strong> Je moet je naam en e-mailadres invullen', $('#register_form'));
        set_button_state(button, 'unregistered');
        return;
    }

    // Send AJAX-call to register for meal
    $.ajax({
        type: 'POST',
        url: '/aanmelden',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
            name: $('#name').val(),
            email: $('#email').val(),
            handicap: $('#handicap').val(),
            meal_id: button.data('id')
        }),
        success: function() {
            set_button_state(button, 'registered');

            // Show warning that email confirmation is needed
            show_notification('error', '<strong>Let op:</strong> Je moet je aanmelding nog bevestigen. We hebben je hiervoor een e-mail gestuurd.', $('#register_form'));
        },
        error: function(error){
            fatal_error(error);
            set_button_state(button, 'unregistered');
        },
    });
}

/**
 * Sets the state of a button
 * @param {DOMObject} button the button to change
 * @param {String} state either one of 'unregistered', 'busy' or 'registered'
 * @return {undefined}
 */
function set_button_state(button, state)
{
    button.removeClass();
    button.addClass(state);

    switch (state)
    {
        case 'unregistered':
        {
            button.html('nom!');
            break;
        }
        case 'busy':
        {
            button.html('nom! <img src="images/spinner.gif" height="16" width="16" alt="">');
            break;
        }
        case 'registered':
        {
            button.html('&#10004;');
            break;
        }
    }
}
