document.addEventListener('DOMContentLoaded', function(){

    // Set the total amount of meals in the administration interface
    document.getElementById('count').addEventListener('change', function(){
        this.form.submit();
    });

    // Confirm the intent of the user before destroying meals
    var confirm_links = document.getElementsByClassName('destroy-meal');
    for (var i = confirm_links.length - 1; i >= 0; i--) {
        confirm_links[i].addEventListener('click', function(event) {
            if (! confirm('Weet je zeker dat je deze maaltijd wilt verwijderen?')) {
                event.preventDefault();
            }
        });
    };
});
