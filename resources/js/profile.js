window.Profile = {

    init: function () {
        $('.profile #token-reset').on('click', Profile.resetToken);
    },

    resetToken: function (event) {
        event.preventDefault();

        // Send AJAX-call to register for meal
        $.ajax({
            type: 'POST',
            url: '/token/reset',
            contentType: 'application/json',
            success: function (response) {
                $('.profile #token-secret').html(response.token);
                $('.profile #token-output').show();
            },
            error: function (error) {
                App.fatalError(error);
            },
        });
    },
};

$(document).ready(Profile.init);
