<?php

namespace App\Http\Controllers;

use App\Repositories\AuctionRepository;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use Redirect;

class BidController extends Controller
{
    /**
     * @var AuctionRepository
     */
    private $auctions;

    public function __construct(AuctionRepository $auctions)
    {
        $this->auctions = $auctions;
    }

    /**
     * Display a listing of all bids for an auction.
     *
     * @param $auctionId
     * @return Response
     */
    public function index($auctionId)
    {
        //
    }

    /**
     * Store a new bid in storage.
     *
     * @param  Request $request
     * @param $id
     * @return Response
     */
    public function store(Request $request, $id)
    {
        $auctionId = $id;

        // Validate the auction ID
        if (!$this->auctions->isValidAuctionId($id)) {
            return Redirect::back()->with(['auction_id_error' => "Invalid auction ID: {$id}"]);
        };

        $userId = Auth::user()->id;

        // Validate the bid amount. Redirects back if validation fails.
        $this->validate($request, [
            'bid' => "required
                     |money
                     |not_auction_owner:{$auctionId},{$userId}
                     |allowable_bid_amount:{$auctionId},{$userId}
                     |auction_is_open:{$auctionId}"
        ]);

        dd("Validation passed");

    }

}
