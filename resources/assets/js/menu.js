$(document).ready(function(){

    // Collapse menu when screen is small
    $(window).on('resize', function(event){
        if ($('nav').hasClass('user_lock')) {
            return;
        }

        if (this.innerWidth < 880) {
            implode();
        }
        else {
            explode();
        }
    });

    // Trigger initial event
    $(window).trigger('resize');

    // Clicking the hamburger triggers the menu
    $('.hamburger').on('click', function(event){
        $('nav').toggleClass('user_lock');
        $('nav, section').toggleClass('collapsed');
        $(window).trigger('resize');
    })
});

function implode()
{
    $('nav, section').addClass('collapsed');
}

function explode(show_burger)
{
    $('nav, section').removeClass('collapsed');
}

function lock_nav()
{
    $('nav').addClass('user_lock');
}

function unlock_nav()
{
    $('nav').removeClass('user_lock');
}
