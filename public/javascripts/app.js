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
