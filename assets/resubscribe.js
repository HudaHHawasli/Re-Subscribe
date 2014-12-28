jQuery(document).ready(function ($) {

    var $inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];

    // If cookie exists don't show the modal
    if(document.cookie.indexOf(resubscribe.key) != -1) {
        return;
    }

    // display popup after 20 seconds.
    setTimeout(function () {
        $inst.open();
    }, 20000);

    // In footer-box, if close link clicked, then hide the footer-box
    $('#resubscribe-footer-box').find('.close a').click(function() {
        $('#resubscribe-footer-box').fadeOut();
    });

    // In footer-box, if subscribe link clicked, show the modal then hide the footer-box
    $('#resubscribe-footer-box').find('.subscribe a').click(function() {
        $inst.open();
        $('#resubscribe-footer-box').hide();
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