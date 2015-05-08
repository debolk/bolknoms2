// Start behaviour when DOM is ready
document.addEventListener('DOMContentLoaded', function(){

    /*
     * Click handler and submission process for buttons
     */
    var buttons = document.querySelectorAll('button');
    Array.prototype.forEach.call(buttons, function(button, i){
        button.addEventListener('click', function(event){
            event.preventDefault();

            // Set button to working state
            set_button_state(button, 'busy');

            // Send AJAX-call to register for meal
            var request = new XMLHttpRequest();
            request.open('POST', '/aanmelden', true);
            request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                    set_button_state(button, 'selected');
                    show_chef();
                }
                else {
                    show_fatal_error(request.responseText);
                    window.location.reload();
                }
            };
            request.onerror = show_fatal_error;
            request.send(JSON.stringify({meal_id: button.dataset.id}));
        });
    });
});

/**
 * Sets the state of a button
 * @param {DOMObject} button the button to change
 * @param {String} state either one of 'normal', 'busy' or 'selected'
 * @return {undefined}
 */
function set_button_state(button, state)
{
    switch (state)
    {
        case 'normal':
        {
            button.className = '';
            button.innerHTML = 'nom!';
            button.disabled = false;
            break;
        }
        case 'busy':
        {
            button.className = 'busy';
            button.innerHTML = 'nom! <img src="images/spinner.gif" height="16" width="16" alt="">';
            button.disabled = true;
            break;
        }
        case 'selected':
        {
            button.className = 'selected';
            button.innerHTML = 'nom! &#10004;';
            button.disabled = true;
            break;
        }
    }
}

function show_fatal_error(details)
{
    alert("Fatale fout. Het is onduidelijk of je correct bent aangemeld voor de maaltijd. Bel of mail het bestuur via de contactgegevens op de pagina.\n\nTechnische details: " + details.toString());
}

/**
 * Show a complimentary video of the Swedish Chef as a thank you for subscribing
 */
function show_chef()
{
    // No need for two chefs at the same time
    if (chef_is_visible()) {
        return;
    }

    // Choose random Swedish Chef movie
    var codes = ['j1KSaUEu_T4', 'AvDvTnTGjgQ', 'mbs64GvGgPU', 'mXfHyDCcTGQ',
                 '2Qj8PhxSnhg', 'CAsYwW7pt7o', 'qT_n__vsguk', 'IwGdHAHg0ig'];
    var random_code = codes[Math.floor(Math.random()*codes.length)];

    // Show iframe with that movie
    var chef = document.getElementById('chef');
    var video = chef.getElementsByTagName('iframe')[0];
    chef.style.display = 'block';
    video.setAttribute('src', 'http://www.youtube.com/embed/' + random_code + '?autoplay=0&controls=1');
}

/**
 * Checks if chef is already shown
 * @return {Boolean} [description]
 */
function chef_is_visible()
{
    return (document.getElementById('chef').style.display != '');
}
