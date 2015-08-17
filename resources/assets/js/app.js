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

    // Validate forms
    $('#user-login-form').validate({
        rules: {
            username: {
                required: true,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 50
            }
        },
        highlight: function (element) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function (element) {
            $(element).removeClass('error-highlight');
        }
    });

    $('#user-register-form').validate({
        rules: {
            username: {
                required: true,
                maxlength: 50
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 50
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            }
        },
        highlight: function (element) {
            $(element).addClass('error-highlight');
        },
        unhighlight: function (element) {
            $(element).removeClass('error-highlight');
        }
    });

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
                required: true,
                money: true
            }
        },
        messages: {
            bid: {
                required: "A bid is required.",
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
            date_ending: {
                required: true
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
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "date_ending" )
                error.insertAfter(element.parent());
            else
                error.insertAfter(element);
        }
    });

    $('#update-auction-form').validate({
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
            date_ending: {
                required: false
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
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "date_ending" )
                error.insertAfter(element.parent());
            else
                error.insertAfter(element);
        }
    });

    // Calendar date picker widget
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    $('#auctionFormDateEndingSelector').datetimepicker({
        format: "DD/MM/YYYY",
        locale: moment.locale('en-AU'),
        minDate: tomorrow.setHours(0, 0, 0, 0),
        maxDate: moment().add(14, 'days'),
        showClear: true,
        showClose: true,
        useCurrent: false,
        keepInvalid: true
    });

    $('input#date_ending').click(function () {
        $('#auctionFormDateEndingSelector').data("DateTimePicker").show();
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