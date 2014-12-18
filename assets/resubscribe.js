jQuery(document).ready(function ($) {

    // save cookie for 100 days
    var days = 1000 * 60 * 60 * 24 * 100;
    var expires = new Date((new Date()).valueOf() + days);

    // display popup after 20 seconds.
    setTimeout(function () {
        var $inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
        $inst.open();
    }, 20000);

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