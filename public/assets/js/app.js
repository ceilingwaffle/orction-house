$(document).ready(function () {

    // Auction box user events
    var auctionBox = $(".s-auction-listing-box");
    if ( auctionBox ) {
        auctionBox.mouseenter(function () {
            $(this).addClass('hover');
        });
        auctionBox.mouseleave(function () {
            $(this).removeClass('hover');
        });
        auctionBox.click(function () {
            //var url = $(this).data('auctionUrl');
            //window.location = url;
        });
    }

    // Auctions filter form validation using jquery.validate
    $('#auctions-filter-form').validate({
        rules: {
            title: {
                maxlength: 50
            },
            min_price: {
                money: true
            },
            max_price: {
                money: true
            }
        },
        messages: {
            title: {
                required: "Title is required."
            }
        },
        highlight: function(element, errorClass) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass('error-highlight');
        }
    });

    // Custom validators
    jQuery.validator.addMethod("money", function(value, element) {
        return this.optional(element) || value.match(/^(\$)?\d+(\.\d{1,2})?$/);
    }, "Must be a valid money format.");

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
//# sourceMappingURL=app.js.map