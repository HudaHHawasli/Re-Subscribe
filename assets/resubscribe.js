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

    // Check if mobile device
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        $('#resubscribe-footer-box').show();
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
    }
    else {
        // display popup after 20 seconds.
        setTimeout(function () {
            $inst.open();
            setCookie(resubscribe.key, '1', resubscribe.expiration_days);
        }, 20000);
    }

    $(document).on('keypress', function (e) {
        if(e.which == 13) {
            var email = $('.remodal').find('input[name="email"]').val();
            if (email) {
                ajax_request(email);
            }
            $inst.close();
        }
    });

    $(document).on('confirm', '.remodal', function () {
        // Handle form submittion here (Ajax?)
        var email = $(this).find('input[name="email"]').val();
        ajax_request(email);
    });

    function ajax_request(value) {
        $.ajax({
                type: "POST",
                url: resubscribe.ajaxurl,
                data: {
                    'action': resubscribe.action,
                    'email': value
                }
        });
    }
});