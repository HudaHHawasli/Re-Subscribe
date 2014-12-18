jQuery(document).ready(function ($) {

    // If cookie exists don't show the modal
    if(document.cookie.indexOf(resubscribe.key) != -1) {
        return;
    }

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