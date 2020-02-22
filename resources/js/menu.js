window.Menu = {

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

$(document).ready(Menu.init);
