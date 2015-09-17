/*
 * Common functionality for every page
 */
function show_notification(message, title, type)
{
    if (type == undefined) {
        type = 'error';
    }

    if (title == undefined) {
        title = 'Foutmelding'
    }

    swal({
        title: title,
        text: message,
        type: type
    });
}

function fatal_error(error)
{
    var error = JSON.parse(error.response);
    show_notification(error.error_details);
}
