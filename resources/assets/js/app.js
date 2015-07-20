$(document).ready(function () {

    // Auction box user events
    var box = $(".s-auction-listing-box");
    if ( box ) {
        box.mouseenter(function () {
            $(this).addClass('hover');
        });
        box.mouseleave(function () {
            $(this).removeClass('hover');
        });
        box.click(function () {
            var url = $(this).data('auctionUrl');
            window.location = url;
        });
    }


});

/**
 * Resets the fields of a form to default values
 *
 * @param element
 */
function clearForm(element) {
    var e = $(element);
    var form = e.parent('form').get(0);
    var $form = $("#" + form.id + " *");

    $form.filter(':input').each(function () {
        var $field = $(this);
        var defaultValue = $field.attr('data-default-value');

        if (defaultValue) {
            $field.val(defaultValue);
        } else {
            $field.val("");
        }

    });
}