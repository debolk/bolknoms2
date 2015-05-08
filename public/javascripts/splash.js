// Start behaviour when DOM is ready
document.addEventListener('DOMContentLoaded', function(){

    // Swedish chef name toggler
    document.getElementsByTagName('abbr')[0].addEventListener('click', function(){
        var a = this.innerHTML;
        var b = this.getAttribute('title');
        this.innerHTML = b;
        this.setAttribute('title', a);
    });
});
