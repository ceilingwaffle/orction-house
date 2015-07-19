$(document).ready(function () {
    var box = $(".s-auction-listing-box");

    if ( box ) {

        console.log('a');

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
//# sourceMappingURL=all.js.map