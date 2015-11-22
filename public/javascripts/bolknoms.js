App = {

    /**
     * Bootstrap the application, set event handlers, etc.
     * @return {undefined}
     */
    init: function() {
        // Common functionality enhancements
        $('.auto-submit').on('change', App.autoSubmit);
        $('.confirm-intent').on('click', App.confirmIntent);
    },

    /**
     * Automatically submmit the parent form when an input changes
     * @param  {Event} event
     * @return {undefined}
     */
    autoSubmit: function(event) {
        this.form.submit();
    },

    /**
     * Ask a user for confirmation before following a click
     * @param  {Event} event
     * @return {undefined}
     */
    confirmIntent: function(event) {
        if (!confirm('Weet je het zeker?')) {
            event.preventDefault();
        }
    },

    /**
     * Show a notification
     * @param  {String} message the body of the message to show
     * @param  {String} title   title of the popup, default "Foutmelding"
     * @param  {String} type    type of message to show
     * @return {[type]}         [description]
     */
    showNotification: function(message, title, type) {
        if (type === undefined) {
            type = 'error';
        }

        if (title === undefined) {
            title = 'Foutmelding';
        }

        swal({
            title: title,
            text: message,
            type: type
        });
    },

    /**
     * Show a fatal error upon AJAX-errors
     * @param  {Event} error
     * @return {undefined}
     */
    fatalError: function (error) {
        error = JSON.parse(error.response);
        showNotification(error.error_details);
    }
};

// Start the application
$(document).on('ready', App.init);
