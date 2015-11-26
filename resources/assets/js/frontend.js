Frontend = {

    /**
     * Setup the frontend javascript interface
     * adding event handlers, etc.
     * @return {undefined}
     */
    init: function() {
        Frontend.hideMealsIfNotLoggedIn();

        // Proceeding without logging in shows a form (name, email, handicaps)
        $('.proceed_anonymous').on('click', Frontend.showAnonymousDetailsForm);

        // Click handler and submission process for buttons
        $('.meal button').on('click', Frontend.processRegistration);

        // If a deadline has passed, revert to showing fancy errors
        $('.meal.deadline_passed button').off('click');
        $('.meal.deadline_passed button.unregistered').on('click', Frontend.tooLate);
        $('.meal.deadline_passed button.registered').on('click', Frontend.tooLate);

        // Show a dialog to get the new handicap of the user
        $('#set_profile_handicap').on('click', Frontend.updateHandicap);
    },

    /**
     * Show an error when a user tries to interact with a meal
     * of which the deadline has passed
     * @param  {Event} event
     * @return {undefined}
     */
    tooLate: function(event) {
        event.preventDefault();
        App.showNotification('Je kunt je niet meer aanmelden of afmelden voor deze maaltijd', 'Deadline verstreken', 'error');
    },

    /**
     * Hide existing meals from UI when the user is not logged in
     * @return {undefined}
     */
    hideMealsIfNotLoggedIn: function() {
        if ($('.user.noone').length > 0) {
            $('.meal').hide();
        }
    },

    /**
     * Reveal the form used for non-bolkaccount subscriptions
     * @param  {Event} event
     * @return {undefined}
     */
    showAnonymousDetailsForm: function(event){
        event.preventDefault();

        $('.anonymous .method').hide(200);
        $('.anonymous form').show(200);
        $('.meal').show(200);
    },

    /**
     * Event handler for when a register button is clicked
     * determine which business logic to follow, and call it
     * @param  {Event} event
     * @return {undefined}
     */
    processRegistration: function(event){

        event.preventDefault();

        // Set button to working state
        var button = $(this);

        // Choose appropriate submission process
        if ($('.user.someone').size() == 1) {
            if (button.hasClass('unregistered')) {
                Frontend.register(button);
            }
            else {
                Frontend.deregister(button);
            }
        }
        else {
            if (button.hasClass('unregistered')) {
                Frontend.registerNonUser(button);
            }
        }
    },

    /**
     * Register for a meal with logged in users
     * @param  {DOMelement} button the button that was clicked
     * @return {undefined}
     */
    register: function(button) {
        Frontend.setButtonState(button, 'busy', false);

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
                Frontend.setButtonState(button, 'registered', true);
            },
            error: function(error) {
                Frontend.setButtonState(button, 'unregistered', true);
                App.fatalError(error);
            },
        });
    },

    /**
     * Deregister from a meal
     * @param  {DOMelement} button
     * @return {undefined}
     */
    deregister: function(button) {
        Frontend.setButtonState(button, 'busy', false);

        // Send AJAX-call to deregister for meal
        $.ajax({
            type: 'POST',
            url: '/afmelden',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
               meal_id: button.data('id')
            }),
            success: function() {
                Frontend.setButtonState(button, 'unregistered', true);
            },
            error: function(error) {
                Frontend.setButtonState(button, 'registered', true);
                App.fatalError(error);
            },
        });
    },

    registerNonUser: function(button) {
        Frontend.setButtonState(button, 'busy', false);

        // Check form fields
        if ($('#name').val() === '' || $('#email').val() === '') {
            App.showNotification('Je moet je naam en e-mailadres invullen');
            Frontend.setButtonState(button, 'unregistered', true);
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
                Frontend.setButtonState(button, 'registered', false);

                // Show warning that email confirmation is needed
                App.showNotification('Je moet je aanmelding nog bevestigen. We hebben je hiervoor een e-mail gestuurd. Volg de link in de e-mail om je aanmelding definitief te maken.', 'Bevestiging nodig', 'warning');
            },
            error: function(error){
                App.fatalError(error);
                Frontend.setButtonState(button, 'unregistered', true);
            },
        });
    },

    /**
     * Sets the state of a button
     * @param {DOMObject} button the button to change
     * @param {String} state either one of 'unregistered', 'busy' or 'registered'
     * @param {Boolean} useable true if clicking the button will do something, false otherwise
     * @return {undefined}
     */
    setButtonState: function(button, state, useable) {
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
    },

    /**
     * Show a dialog and update the handicap of the user
     * on the server based on its input
     * @param  {Event} event
     * @return {undefined}
     */
    updateHandicap: function(event) {
        event.preventDefault();

        var handicap = $('#handicap').data('handicap');
        handicap = prompt('Specificeer je dieetwensen zo exact en duidelijk mogelijk:', handicap);

        // No-op on cancel
        if (handicap === null) {
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

                if (handicap === '') {
                    $('#handicap').html('Geen dieet ingesteld').addClass('no_diet');
                }
                else {
                    $('#handicap').html('&ldquo;' + handicap + '&rdquo;').removeClass('no_diet');
                }
            },
            error: App.fatalError,
        });
    },
};

$(document).on('ready', Frontend.init);