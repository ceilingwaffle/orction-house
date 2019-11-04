# Orction House

PHP Laravel website created for a university assignment in 2005.

www.orction-house.com

Orction House is a website with the ultimate goal of allowing users to easily sell their possessions to other users, and browse and purchase items for sale. Visitors can sign up for an account, browse items available for sale, list an item for sale, and bid on items. Payment arrangements are intended to be completed through private communication between buyers and sellers outside of the website. The motivation for this project is to provide the simplest experience possible for the buying and selling of auctions, targeted at a user-base that finds the functionality of existing sites too complex to use (e.g. Gumtree and eBay). 

The word “Orction” is a portmanteau of the words “orc” and “auction”. Orcs are fictional goblin-like creatures, commonly used in fantasy novels and movies. The choice of using orcs as the theme of the website was made with the intention of livening up the website – to enhance the user experience by promoting a fun environment. 

To tackle the issue of “seller trust”, the website will allow buyers to provide publicly viewable feedback to a seller after the sale of an auction has been completed. This feedback system allows potential buyers to better gauge the trust worthiness of a seller, prior to bidding on an item. This is accomplished by allowing each user to see how much positive, neutral, and negative feedback the seller has received, including each associated “feedback message”, and which auction listing each piece of feedback pertains to.

# How it Works
## Functionality
- User Authentication
	- Create a new account.
	- Log in/log out an account.
- Create/update an auction.
	- Enter an item name, description, category, condition, starting price and end date.
	- Upload a photo of the item.
- View all auctions created.
	- Filter results shown by: item name, category, min/max price.
	- Sort results shown by: item name, category, min/max price, auction status, time ending, and apply a sort direction of ascending or descending.
- View the details of an existing auction.
- Provide feedback to a user for a given auction, with permission depending on the defined business rules.
- View feedback provided to each user.
	- Filter results by: positive only, neutral only, negative only.
- View all bids placed on an auction.
- Place a bid on an auction, with permissions and validation depending on the defined business rules.

## Business Rules

- User account creation:
	- Username must be unique, and between 1 and 50 characters in length.
	- Password must be between 6 and 50 characters in length.
- Auctions:
	- An auction is considered finished when the auction end time is reached.
	- An auction is considered "expired" (no winner) if it finished with no bids.
	- Bidding is no longer allowed if an auction has ended.
	- The highest bidder at the time of the auction ending is the winner.
- Bidding:
	- Minimum bid = current bid + $0.50. If no bids, then minimum bid = start price + $0.50.
	- Maximum bid = $999999.99.
	- Users cannot bid on their own auctions.
	- Bidding is no longer allowed if the auction end date has passed.
- User Feedback:
	- If a user wins an auction, he/she can optionally submit feedback to the auction seller.
	- Feedback can only be submitted by an auction winner to its seller.
	- Only one piece of feedback can be submitted per auction win.
	- Max feedback message length = 200 characters.
	- User provides a feedback rating of "positive", "neutral", or "negative".
- Viewing a single auction:
	- The "Update Auction" button will only appear on the auction details page if:
	- The auction has not finished.
	- The logged-in user is the creator of the auction.
- Creating/updating an auction:
	- The end date must be between 1 day and 14 days from today (inclusive).
	- The start price cannot be modified after the auction is created.
	- The end date cannot be modified if the auction is ending today.

# Design
[db_diagram]: https://imgur.com/5VW31LF.png "Database Diagram"
[er_diagram]: https://imgur.com/cvnJCTU.png "Entity Relationship Diagram"
[uc_diagram]: https://imgur.com/MRLDm1I.png "Use Case Diagram"
[site_map]: https://imgur.com/6X4g7cC.png "Site Map"
## Use Case Diagram
![Use Case Diagram][uc_diagram]
## Database Diagram
![Database Diagram][db_diagram]
## Entity Relationship Diagram
![Entity Relationship Diagram][er_diagram]
## Site Map
![Site Map][site_map]

# Page Mockups
## Home Page
![Home Page](https://i.imgur.com/X5AW2Cn.png "Home Page")
## Sign Up
![Sign Up](https://i.imgur.com/eDhCrOQ.png "Sign Up")
## Log In
![Log In](https://i.imgur.com/BPBwUdN.png "Log In")
## View All Auctions
![View All Auctions](https://i.imgur.com/4FLTLqN.png "View All Auctions")
## Create New Auction
![Create New Auction](https://i.imgur.com/2ki7VrT.png "Create New Auction")
## View Bids For Auction
![View Bids For Auction](https://i.imgur.com/cBR9gXZ.png "View Bids For Auction")
## View User Feedback
![View User Feedback](https://i.imgur.com/URc8vCA.png "View User Feedback")
## Create User Feedback
![Create User Feedback](https://i.imgur.com/P0rLGOL.png "Create User Feedback")
## View Auction
![View Auction](https://i.imgur.com/IUFDZv7.png "View Auction")
## Update Auction
![Update Auction](https://i.imgur.com/6rseZ5M.png "Update Auction")

# Technologies Used
## LAMP Stack
- Ubuntu 14.04.03 LTS
- Apache 2.4.7
- MySQL 5.5.44
- PHP 5.56.12
- Laravel 5.1

## Dependencies
- Laravel
- jQuery
- Twitter Bootstrap
- jQuery Validate
- MomentJS
- Bootstrap DateTimePicker

## Software Design Patterns
- MVC (Laravel)
- Repositories
- Transformers
- Blade view templates (Laravel)

## SQL Queries
- Queries stored in various repository classes in directory: ./app/Repositories
e.g. "Get all auctions" query stored within: ./app/Repositories/AuctionRepository.php -> getAuctions()

## Validation
- Most server-side validation is logic stored in the class:
    ./app/Providers/AppServiceProvider.php -> boot()
    ...then called in controller methods using $this->validate(...)
- Most server-side error messages are contained in the file:
    ./resources/lang/en/validation.php
- Client-side validation logic + messages stored in:
    ./resources/assets/js/app.js (using jquery.validate library)
