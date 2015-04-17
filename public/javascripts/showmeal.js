document.addEventListener('DOMContentLoaded', function(){
    // Print action
    document.getElementById('print').addEventListener('click', print_list);

    // Add a new registration
    var form = document.getElementById('new_registration');
    form.addEventListener('submit', add_registration);

    // Remove registration
    var list = document.getElementById('registrations');
    list.addEventListener('click', remove_registration);
});

function print_list()
{
    // Ask for confirmation if there are no names on the list
    if (get_counter() == 0 && !confirm('De lijst is leeg. Weet je zeker dat je deze wilt afdrukken?')) {
        return;
    }
    window.print();
}

/**
 * Add a new registration
 * @param {Event} event
 */
function add_registration(event)
{
    event.preventDefault();

    // Get the values, ignoring whitespace
    var data = {
        meal_id:  this.getAttribute('data-meal_id'),
        name:     document.getElementById('name').value,
        handicap: document.getElementById('handicap').value,
    };

    // Submit request
    var request = new XMLHttpRequest();
    request.open('POST', '/administratie/aanmelden', true);
    request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            document.getElementById('registrations').innerHTML += request.responseText;
            update_counter(+1);
        }
        else {
            alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
        }
    }
    request.onerror = function() {
        alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
    }
    request.send(JSON.stringify(data));
}

/**
 * Remove a registration from this meal
 * @param  {Event} event
 */
function remove_registration(event)
{
    // Only fire on destroy icons
    if (! event.target.classList.contains('remove_registration')) {
        return;
    }

    // Ask for confirmation
    var button = event.target;
    var registration = button.parentNode;
    if (confirm('Weet je zeker dat je '+button.getAttribute('data-name')+' wilt uitschrijven?')) {
        // Remove registration
        var request = new XMLHttpRequest();
        request.open('POST', '/administratie/afmelden/'+button.getAttribute('data-id'), true);
        request.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                registration.remove();
                update_counter(-1);
            }
            else {
                alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
            }
        }
        request.onerror = function() {
            alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
        }
        request.send();
    }
}

function get_counter()
{
    var counter = document.getElementById('count');
    return parseInt(counter.innerHTML);
}

/**
 * Updates the number of registrations in the interface
 * @param  {int} increment the number to add to the counter, can be negative
 */
function update_counter(increment)
{
    var counter = document.getElementById('count');
    var value = parseInt(counter.innerHTML);
    value += increment;
    counter.innerHTML = value;
}