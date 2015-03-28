document.addEventListener('DOMContentLoaded', function(){
    // Print action
    document.getElementById('print').addEventListener('click', function(){
        window.print();
    });
});

// function add_registration_if_enter(data) {
//     // If enter is pressed
//     if (data.which == 13) {
//         // Trigger the submit-function
//         var row = $(this).parents('.new_registration');
//         $('input[type="submit"]',row).click();
//     }
// }

 // // Remove a registration
    // var destroy_links = document.getElementsByClassName('destroy-registration');
    // for (var i = destroy_links.length - 1; i >= 0; i--) {
    //     destroy_links[i].addEventListener('click', remove_registration);
    // };

    // // // Submit a new registration when pressing enter
    // // var inputs = document.querySelectorAll('.new_registration input[type="text"]');
    // // for (var i = inputs.length - 1; i >= 0; i--) {
    // //     inputs[i].addEventListener('keyup', add_registration);
    // // };

    // // Collapsable tables
    // hide_subtables();
    // var expanders = document.getElementsByClassName('expander');
    // for (var i = expanders.length - 1; i >= 0; i--) {
    //     expanders[i].addEventListener('click', toggle_subtable);
    // };

// function add_registration(data) {
//     // Only process when [Enter] is pressed
//     if (data.which != 13) {
//         return;
//     }

//     // // Get the values, ignoring whitespace
//     // var form = $(this).parents('.new_registration');
//     // var meal_id = $(this).parents('tbody').attr('data-id');
//     // var name = $('input[name="name"]',form).val().trim();
//     // var handicap = $('input[name="handicap"]',form).val().trim();

//     // if (name !== '') {
//     //     // Update the server
//     //     $.post('/administratie/aanmelden',{
//     //             meal_id: meal_id,
//     //             name: name,
//     //             handicap: handicap
//     //         },
//     //         function(new_row){
//     //             // Update meal
//     //             $('tbody[data-id="'+meal_id+'"]').replaceWith(new_row);
//     //             // Re-open the list of names
//     //             $('.expander', 'tbody[data-id="'+meal_id+'"]').click();
//     //         },'html');
//     // }
// }


// /**
//  * Remove a registration from the server
//  */
// function remove_registration(event)
// {
//     // Do not follow the link
//     event.preventDefault();

//     // Find relevant elements
//     var meal = ancestorWithClass(this, 'meal');
//     console.log(meal);
//     var registration = ancestorWithClass(this, 'registration');

//     // Read values
//     var meal_id = meal.dataset.id;
//     var name = registration.querySelector('.meal').innerHTML();

//     // Ask for confirmation
//     if (confirm('Weet je zeker dat je '+name+' wilt uitschrijven?')) {

//             var request = new XMLHttpRequest();
//             request.open('POST', this.getAttribute('href'), true);
//             request.onload = function() {
//                 if (this.status >= 200 && this.status < 400) {
//                     registration.remove();
//                 }
//                 else {
//                     alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
//                 }
//             }
//             request.onerror = function() {
//                 alert('Er is een fout opgetreden. Probeer de pagina te verversen.')
//             }
//     }

// }
