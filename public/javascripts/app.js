/*
 * Common functionality for every page
 */
function show_notification(type, message, element)
{
    $('.notification').remove();
    var warning = $('<div>');
    warning.addClass('notification ' + type);
    warning.html(message);
    warning.insertBefore(element);
    warning.hide();
    warning.show(500);
}

function fatal_error(error)
{
    var error = JSON.parse(error.response);
    show_notification('error', '<strong>Fout:</strong> ' + error.error_details + '<br><br> Technische details: ' + error.error, $('#register_form'));
}
