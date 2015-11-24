Administration = {

    /**
     * Start the Adminstration specific log
     * @return {undefined}
     */
    init: function() {
        // Bind event handlers
        $('#print').on('click', Administration.printList);
        $('#new_registration').on('submit', Administration.addRegistration);
        $('#registrations').on('click', '.remove_registration', Administration.removeRegistration);
        $('#subscribe_anonymous').on('click', Administration.addAnonymousRegistration);
    },

    /**
     * Trigger print dialog for the list of registrations
     * asking for confirmation if no registrations are present
     * @return {undefined}
     */
    printList: function() {
        var counter = parseInt($('#count').html());
        if (counter === 0 && !confirm('De lijst is leeg. Weet je zeker dat je deze wilt afdrukken?')) {
            return;
        }
        window.print();
    },

    /**
     * Add a new registration to a meal
     * @param {Event} event
     * @return {undefined}
     */
    addRegistration: function(event) {
        event.preventDefault();

        var form = this;

        // Send AJAX-call to register for meal
        $.ajax({
            type: 'POST',
            url: '/administratie/aanmelden',
            contentType: 'application/json',
            dataType: 'html',
            data: JSON.stringify({
                meal_id:  $(this).attr('data-meal_id'),
                user_id: $('#user_id').val()
            }),
            success: function(response) {
                $('#registrations tbody').append(response);
                Administration.update_counter(+1);
                form.reset();
            },
            error: App.fatalError
        });
    },

    /**
     * Add a new anonymous registration to a meal
     * @param {Event} event
     * @return {undefined}
     */
    addAnonymousRegistration: function(event) {

        event.preventDefault();

        var name = prompt('Naam');
        if (!name) {
            return;
        }

        var handicap = prompt('Evt. voedselhandicaps');

        // Send AJAX-call to register for meal
        $.ajax({
            type: 'POST',
            url: '/administratie/aanmelden',
            contentType: 'application/json',
            dataType: 'html',
            data: JSON.stringify({
                meal_id:  $(this).attr('data-meal_id'),
                name:     name,
                handicap: handicap
            }),
            success: function(response) {
                $('#registrations tbody').append(response);
                Administration.update_counter(+1);
            },
            error: App.fatalError
        });
    },

    /**
     * Remove a registration from a meal
     * @param  {Event} event
     * @return {undefined}
     */
    removeRegistration: function(event) {
        event.preventDefault();

        // Ask for confirmation
        var registration = $(this).parents('.registration');
        if (! confirm('Weet je zeker dat je '+$(this).attr('data-name')+' wilt uitschrijven?')) {
            return;
        }

        // Remove registration
        $.ajax({
            type: 'POST',
            url: '/administratie/afmelden/'+$(this).attr('data-id'),
            contentType: 'application/json',
            dataType: 'html',
            success: function(response) {
                registration.remove();
                Administration.update_counter(-1);
            },
            error: App.fatalError
        });
    },

    /**
     * Updates the number of registrations in the interface
     * @param  {int} increment the number to add to the counter, can be negative
     */
    updateCounter: function(increment) {
        var counter = $('#count');
        var value = parseInt(counter.html());
        value += increment;
        counter.html(value);
    }
};

$(document).on('ready', Administration.init);

//# sourceMappingURL=administration.js.map
