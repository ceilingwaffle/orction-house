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
    }

    // Custom validators
    jQuery.validator.addMethod("money", function (value, element) {
        return this.optional(element) || value.match(/^(\$)?\d+(\.\d{1,2})?$/);
    }, "Must be a valid money format (e.g. $1.23).");

    jQuery.validator.addMethod("integer", function (value, element) {
        return this.optional(element) || value.match(/^\d+$/);
    }, "Must be a whole number.");

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
        highlight: function (element) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function (element) {
            $(element).removeClass('error-highlight');
        }
    });

    $('#place-bid-form').validate({
        errorLabelContainer: ".s-bid-errors",
        wrapper: "li",
        rules: {
            bid: {
                money: true
            }
        },
        messages: {
            bid: {
                money: "Bid must be a valid monetary amount (e.g. $1.23)."
            }
        },
        highlight: function (element) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function (element) {
            $(element).removeClass('error-highlight');
        }
    });

    // Auctions filter form validation using jquery.validate
    $('#create-auction-form').validate({
        rules: {
            item_name: {
                required: true,
                maxlength: 50
            },
            description: {
                required: true,
                maxlength: 1000
            },
            category: {
                required: true,
                min: 1
            },
            starting_price: {
                required: true,
                money: true
            },
            days: {
                required: true,
                integer: true,
                range: [ 1, 14 ]
            },
            photo: {
                required: false,
                accept: "image/*"
            }
        },
        messages: {
            photo: {
                accept: "The file must be an image."
            }
        },
        highlight: function (element) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function (element) {
            $(element).removeClass('error-highlight');
        }
    });

});

/**
 * Reloads the current page at the current pagination page number
 *
 * @returns {boolean}
 */
function reloadAtCurrentPage() {

    var page = (location.search.split('page=')[ 1 ] || '').split('&')[ 0 ];

    if ( page.length == 0 ) {
        page = 1;
    }

    window.location = '//' + location.host + location.pathname + '?page=' + page;

    return false;
}
//# sourceMappingURL=app.js.map