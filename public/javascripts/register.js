$(document).ready(function(){

    // Temporary feature explanation
    $('.someone .photo').on('click', function(){
        alert('Je foto komt direct uit de ledenadministratie, cool hè? Je kunt je foto veranderen op gosa.i.bolkhuis.nl. Je moet daarvoor wel op het interne netwerk zitten (Bolknet, kabel of VPN).');
    });

    // Set the profile handicap
    $('#set_profile_handicap').on('click', update_handicap);

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
            else {
                deregisterNonUser(button);
            }
        }

    });
});

function update_handicap(event)
{
    event.preventDefault();

    var handicap = prompt('Specificeer je dieetwensen zo exact en duidelijk mogelijk:');
    $.ajax({
        type: 'POST',
        url: '/handicap',
        contentType: 'application/json',
        dataType: 'application/json',
        data: JSON.stringify({handicap: handicap}),
        success: function() {
            var text;
            if (handicap == '') {
                text = 'Geen dieetwensen';
            }
            else {
                text = 'Dieet: ' + handicap;
            }
            $('#handicap_text').html(text);
        },
        error: fatal_error,
    });
}

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
        success: function(response) {
            button.data('salt', JSON.parse(response).salt);
            set_button_state(button, 'registered');
        },
        error: fatal_error,
    });
}

function deregisterNonUser(button)
{
    set_button_state(button, 'busy');

    // Send AJAX-call to register for meal
    $.ajax({
        type: 'POST',
        url: '/afmelden',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify({
            salt: button.data('salt'),
            meal_id: button.data('id')
        }),
        success: function() {
            set_button_state(button, 'unregistered');
        },
        error: fatal_error,
    });
}


function fatal_error(error)
{
    var error = JSON.parse(error.response);
    alert('Fout: ' + error.error_details + '\n\n Technische details: ' + error.error);
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
            button.html('nom! &#10004;');
            break;
        }
    }
}
