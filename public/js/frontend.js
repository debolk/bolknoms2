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

    // If a deadline has passed, remove the normal click handler
    $('.meal.deadline_passed button').off('click');
    $('.meal.deadline_passed button.unregistered').on('click', function(event){
        event.preventDefault();
        App.showNotification('Je kunt je niet meer aanmelden voor deze maaltijd', 'Deadline verstreken', 'error');
    });
    $('.meal.deadline_passed button.registered').on('click', function(event){
        event.preventDefault();
        App.showNotification('Je kunt je niet meer afmelden voor deze maaltijd', 'Deadline verstreken', 'error');
    });
});

function register(button)
{
    set_button_state(button, 'busy', false);

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
            set_button_state(button, 'registered', true);
        },
        error: function(error) {
            set_button_state(button, 'unregistered', true);
            App.fatalError(error);
        },
    });
}

function deregister(button)
{
    set_button_state(button, 'busy', false);

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
            set_button_state(button, 'unregistered', true);
        },
        error: function(error) {
            set_button_state(button, 'registered', true);
            App.fatalError(error);
        },
    });
}

function registerNonUser(button)
{
    set_button_state(button, 'busy', false);

    // Check form fields
    if ($('#name').val() == '' || $('#email').val() == '') {
        App.showNotification('Je moet je naam en e-mailadres invullen');
        set_button_state(button, 'unregistered', true);
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
            set_button_state(button, 'registered', false);

            // Show warning that email confirmation is needed
            App.showNotification('Je moet je aanmelding nog bevestigen. We hebben je hiervoor een e-mail gestuurd. Volg de link in de e-mail om je aanmelding definitief te maken.', 'Bevestiging nodig', 'warning');
        },
        error: function(error){
            App.fatalError(error);
            set_button_state(button, 'unregistered', true);
        },
    });
}

/**
 * Sets the state of a button
 * @param {DOMObject} button the button to change
 * @param {String} state either one of 'unregistered', 'busy' or 'registered'
 * @param {Boolean} useable true if clicking the button will do something, false otherwise
 * @return {undefined}
 */
function set_button_state(button, state, useable)
{
    button.removeClass();
    button.addClass(state);
    if (!useable) {
        button.addClass('unusable');
    }

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
        error: App.fatalError,
    });
}

//# sourceMappingURL=frontend.js.map
