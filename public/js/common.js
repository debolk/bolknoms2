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
        App.showNotification(error.error_details);
    }
};

// Start the application
$(document).on('ready', App.init);

Menu = {

    /**
     * Start the menu
     * @return {undefined}
     */
    init: function() {
        // Collapse menu when screen is small
        $(window).on('resize', Menu.collapse);
        $(window).trigger('resize');    // Initial event to trigger initial state

        // Clicking the hamburger triggers the menu
        $('.hamburger').on('click', Menu.toggle);
    },

    /**
     * Collapse the menu to the closed state
     * @return {undefined}
     */
    implode: function() {
        $('nav, section').addClass('collapsed');
    },

    /**
     * Enlarge the menu to the normal state
     * @return {undefined}
     */
    explode: function() {
        $('nav, section').removeClass('collapsed');
    },

    /**
     * Lock the menu in place (does not change on window resize)
     * @return {undefined}
     */
    lockNavigation: function(){
        $('nav').addClass('user_lock');
    },

    /**
     * Unlock the menu (does change when window resizes)
     * @return {undefined}
     */
    unlockNavigation: function() {
        $('nav').removeClass('user_lock');
    },

    /**
     * Main function that shows or collapses the menu based on settings
     * @param  {Event} event
     * @return {undefined}
     */
    collapse: function(event){
        if ($('nav').hasClass('user_lock')) {
            return;
        }

        if (this.innerWidth < 880) {
            Menu.implode();
        }
        else {
            Menu.explode();
        }
    },

    /**
     * Toggle the menu after user clicks the burger
     * @param  {Event} event
     * @return {undefined}
     */
    toggle: function(event){
        $('nav').toggleClass('user_lock');
        $('nav, section').toggleClass('collapsed');
        $(window).trigger('resize');
    },
};

$(document).on('ready', Menu.init);

//# sourceMappingURL=common.js.map
