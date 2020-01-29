import Swal from 'sweetalert2';

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

        // Chef gimmick
        $('.chef').on('click', Frontend.randomChefPromotion);
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
        if ($('.user.someone').length == 1) {
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

                // Add the users photo to a meal
                var meal = button.parents('.meal')[0];
                var image = $('.user .photo').clone().addClass('me')

                // Add to UI invisibly
                image.css('display', 'none');
                $('.registrations', meal).append(image);
                image.fadeIn(600);
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

                // Remove the users' photo from a meal
                var meal = button.parents('.meal')[0];
                $('.registrations .me', meal).fadeOut(600, function(){
                    $(this).remove();
                });
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
                button.html('Aanmelden');
                break;
            }
            case 'busy':
            {
                button.html('<img src="images/spinner.gif" height="16" width="16" alt=""> nom nom nom');
                break;
            }
            case 'registered':
            {
                button.html('&#10004; Je eet mee');
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

        var existing_handicap = $('#handicap').attr('data-handicap');

        Swal.fire({
            title: 'Dieetwensen instellen',
            text: 'Specificeer je dieetwensen zo exact en duidelijk mogelijk',
            type: 'input',
            showCancelButton: true,
            closeOnConfirm: true,
            inputValue: existing_handicap,
        }, function(new_handicap) {
            // No-op on cancel
            if (new_handicap === false) {
                return;
            }

            // No-op when not changed
            if (new_handicap === existing_handicap) {
                return;
            }

            // Update server
            $.ajax({
                type: 'POST',
                url: '/handicap',
                contentType: 'application/json',
                dataType: 'application/json',
                data: JSON.stringify({handicap: new_handicap}),
                success: function() {
                    // Store in data
                    $('#handicap').data('handicap', new_handicap);

                    // Update UI
                    if (new_handicap === '') {
                        $('#handicap').html('Geen dieet ingesteld').addClass('no_diet');
                    }
                    else {
                        $('#handicap').html('&ldquo;' + new_handicap + '&rdquo;').removeClass('no_diet');
                    }
                },
                error: App.fatalError,
            });
        });
    },

    /**
     * Show a random Swedish Chef movie
     * @param  {Event} event
     * @return {undefined}
     */
    randomChefPromotion: function(event) {
        event.preventDefault();

        // Movies to play
        var recipes = [
            {name: 'Chocolate Moose', id: 'CAsYwW7pt7o'},
            {name: 'Pöpcørn', id: 'B7UmUX68KtE'},
            {name: 'Meatballs', id: 'sY_Yf4zz-yo'},
        ];

        // Choose a random entry
        var recipe = recipes[Math.floor(Math.random() * recipes.length)];

        // Show popup
        Swal.fire({
            title: "How to make " + recipe.name,
            text: "<iframe width=420 height=315 src=\"https://www.youtube-nocookie.com/embed/"+recipe.id+"?autoplay=1&rel=0&amp;controls=0&amp;showinfo=0\" frameborder=0 allowfullscreen></iframe>",
            html: true,
        });
    }
};

$(document).ready(Frontend.init);
