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
 * Reloads the current page at the current pagination page number
 *
 * @returns {boolean}
 */
function reloadAtCurrentPage() {

    var page = (location.search.split('page=')[1]||'').split('&')[0];

    if (page.length == 0) {
        page = 1;
    }

    window.location = '//' + location.host + location.pathname + '?page=' + page;

    return false;
}