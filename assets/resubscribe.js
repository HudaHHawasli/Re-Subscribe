jQuery(window).load(function () {

    // save cookie for 100 days
    var days = 1000 * 60 * 60 * 24 * 100;
    var expires = new Date((new Date()).valueOf() + days);

    // set the cookies for the domain (. is left before domain in purpose)
    document.cookie = resubscribe.key + "=true;domain=." + resubscribe.key + ";expires=" + expires.toUTCString();

    // display popup after 20 seconds.
    setTimeout(function () {
    }, 2000);
    var options = {
        hashTracking: false
    };
    jQuery('[data-remodal-id=modal]').remodal(options).open();

});