// Start behaviour when DOM is ready
document.addEventListener('DOMContentLoaded', function(){

    /*
     * Check for logged-in status
     */
    var request = new XMLHttpRequest();
    request.open('GET', '/currentuser', true);
    request.onload = function() {
        if (request.status >= 200 && request.status < 400) {

            window.currentUser = null;

            var status = document.getElementById('login_status');

            if (this.response !== '') {

                // Store current user for session
                window.currentUser = this.response;

                // Show login status
                status.classList.remove('warning');
                status.classList.add('success');
                status.innerHTML = 'Welkom terug ' + this.response + '. ';
                var logout = document.createElement('a');
                logout.innerHTML = 'Ik ben ' + this.response + ' niet';
                logout.href= '/logout';
                status.appendChild(logout);

                // Show buttons to subsribe
                document.getElementById('register_form').style.display = 'block';
            }
            else {
                // Show login button
                status.classList.remove('warning');
                status.classList.add('error');
                status.innerHTML = 'Je bent niet ingelogd. ';

                // Add login link
                var login = document.createElement('a');
                login.innerHTML = 'Inloggen met je Bolk-account';
                login.href= '/login';
                status.appendChild(login);
            }
        }
        else {
            show_fatal_error(request.responseText);
            window.location.reload();
        }
    };
    request.onerror = show_fatal_error;
    request.send();
});

function fatal_error()
{

}