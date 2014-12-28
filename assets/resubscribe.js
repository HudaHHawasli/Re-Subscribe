function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}


jQuery(document).ready(function ($) {

    // If cookie exists don't show the modal
    if(document.cookie.indexOf(resubscribe.key) != -1) {
        return;
    }

    var $inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];

    // display popup after 20 seconds.
    setTimeout(function () {
        $inst.open();
        setCookie(resubscribe.key, '1', resubscribe.expiration_days);
    }, 20000);

    // In footer-box, if close link clicked, then hide the footer-box
    $('#resubscribe-footer-box').find('.close a').click(function() {
        $('#resubscribe-footer-box').fadeOut();
        return false;
    });

    // In footer-box, if subscribe link clicked, show the modal then hide the footer-box
    $('#resubscribe-footer-box').find('.subscribe a').click(function() {
        $inst.open();
        setCookie(resubscribe.key, '1', resubscribe.expiration_days);
        $('#resubscribe-footer-box').hide();
        return false;
    });

    $(document).on('confirm', '.remodal', function () {
        // Handle form submittion here (Ajax?)
        $.ajax({
            type: "POST",
            url: resubscribe.ajaxurl,
            data: {
                'action': resubscribe.action,
                'email': $(this).find('input[name="email"]').val()
            }
        });
    });

});