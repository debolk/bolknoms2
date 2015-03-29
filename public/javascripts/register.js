// Start behaviour when DOM is ready
document.addEventListener('DOMContentLoaded', function(){

    var name_input = document.getElementById('name');

    /*
     * Remember previously stored name using localstorage
     * we focus the name input field if no previous name is stored
     * and blur it if one is available
     */
    if (supports_html5_storage()) {
        var name = window.localStorage.getItem('bolknoms_name');

        if (name != null || name == '') {
            name_input.value = name;
            name_input.blur();
        }
        else {
            name_input.focus();
        }

        // On keyup in field, store name in localstorage
        name_input.addEventListener('keyup', function(){
            window.localStorage.setItem('bolknoms_name', name_input.value.trim());
        });
    }
    else {
        name_input.focus();
    }

    /*
     * Client-side validation: show error when the name_input is blurred and empty
     */
    name_input.addEventListener('blur', function(){
        if (this.value.trim() == '') {
            toggle_name_error(true);
        }
    });
    name_input.addEventListener('keyup', function(){
        if (this.value.trim() != '') {
            toggle_name_error(false);
        }
    });

    /*
     * Hide handicap UI until needed
     */
    var handicap_checkbox = document.getElementById('handicap_checkbox');
    handicap_checkbox.addEventListener('change', toggle_handicap);
    toggle_handicap.apply(handicap_checkbox, [null, true]); // Checkbox might be checked on page load, force a check

    /*
     * Click handler and submission process for buttons
     */
    var buttons = document.querySelectorAll('button');
    Array.prototype.forEach.call(buttons, function(button, i){
        button.addEventListener('click', function(event){
            event.preventDefault();

            // Set button to working state
            set_button_state(button, 'busy');

            // Grab variables
            var data = {
                name: name_input.value,
                handicap: document.getElementById('handicap_text').value,
                meal_id: button.dataset.id
            };

            // Validate presence of name
            if (data.name == '') {
                toggle_name_error(true);
                set_button_state(button, 'normal');
                return;
            }

            // Send AJAX-call to register for meal
            var request = new XMLHttpRequest();
            request.open('POST', '/aanmelden', true);
            request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');

            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                    set_button_state(button, 'selected');
                }
                else {
                    show_fatal_error(request.responseText);
                    window.location.reload();
                }
            };
            request.onerror = show_fatal_error;
            request.send(JSON.stringify(data));
        });
    });
});

/**
 * Check whether the browsers allows us to use localstorage
 * @return {boolean} true if localstorage is supported
 */
function supports_html5_storage()
{
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    }
    catch (e) {
        return false;
    }
}

/**
 * Toggles the appearance of the error related to names
 * @param  {boolean} error whether the error must be shown
 * @return {undefined}
 */
function toggle_name_error(error)
{
    var error_message = document.querySelectorAll('.error_explanation')[0];
    if (error) {
        error_message.style.display = 'inline';
    }
    else {
        error_message.style.display = 'none';
    }
}

/**
 * Toggles the handicap entry field
 * @param  {Event}  event   event that triggered this, unused (can be null)
 * @param  {Boolean} initial whether this is the initial check done on page load
 * @return {undefined}
 */
function toggle_handicap(event, initial = false)
{
    var handicap_text = document.getElementById('handicap_text');

    if (this.checked == true) {
        handicap_text.style.display = 'block';
        if (! initial) {
            handicap_text.focus();
        }
    }
    else {
        handicap_text.style.display = 'none';
    }
}

/**
 * Sets the state of a button
 * @param {DOMObject} button the button to change
 * @param {String} state either one of 'normal', 'busy' or 'selected'
 * @return {undefined}
 */
function set_button_state(button, state = 'normal')
{
    switch (state)
    {
        case 'normal':
        {
            button.classList.remove('selected');
            button.classList.remove('busy');
            button.innerHTML = 'nom!';
            button.disabled = false;
            break;
        }
        case 'busy':
        {
            button.classList.add('busy');
            button.classList.remove('selected');
            button.innerHTML = 'nom! <img src="images/spinner.gif" height="16" width="16" alt="">';
            button.disabled = true;
            break;
        }
        case 'selected':
        {
            button.classList.add('selected');
            button.classList.remove('busy');
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
