$(document).ready(function(){

    // Set the total amount of meals in the administration interface
    $('#count').on('change', function(event){
        this.form.submit();
    });


    // Confirm the intent of the user before destroying meals
    $('.destroy-meal').on('click', function(event){
        if (! confirm('Weet je zeker dat je deze maaltijd wilt verwijderen?')) {
            event.preventDefault();
        }
    });
});
