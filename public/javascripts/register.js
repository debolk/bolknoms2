$(document).ready(function(){

    // Click handler for anonymous subscription
    $('.proceed_anonymous').on('click', function(event){
        event.preventDefault();

        $('.anonymous .method').hide();
        $('.anonymous form').show();
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
        // Show a different error depending on whether the form is visible
        if ($('.anonymous form').css('display') !== 'none') {
            show_notification('error', '<strong>Fout:</strong> Je moet je naam en e-mailadres invullen');
        }
        else {
            show_notification('error', '<strong>Fout:</strong> Je moet kiezen voor inloggen of aanmelden zonder Bolkaccount');
        }
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
            show_notification('warning', '<strong>Let op:</strong> Je moet je aanmelding nog bevestigen. We hebben je hiervoor een e-mail gestuurd.');
        },
        error: function(error){
            fatal_error(error);
            set_button_state(button, 'unregistered');
        },
    });
}

function fatal_error(error)
{
    var error = JSON.parse(error.response);
    show_notification('error', '<strong>Fout:</strong> ' + error.error_details + '<br><br> Technische details: ' + error.error);
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

function show_notification(type, message)
{
    $('.notification').remove();
    var warning = $('<div>');
    warning.addClass('notification ' + type);
    warning.html(message);
    warning.insertBefore('.meal');
}
