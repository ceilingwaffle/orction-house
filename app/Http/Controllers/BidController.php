<?php

namespace App\Http\Controllers;

use App\Repositories\AuctionRepository;
use App\Repositories\BidRepository;
use App\Transformers\BidTransformer;
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

    /**
     * @var BidRepository
     */
    private $bids;

    public function __construct(
        AuctionRepository $auctions,
        BidRepository $bids
    ) {
        $this->auctions = $auctions;
        $this->bids = $bids;
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
                     |not_auction_owner:{$auctionId},{$userId}
                     |auction_is_open:{$auctionId}
                     |money
                     |maximum_bid_amount:999999.99
                     |allowable_bid_amount:{$auctionId}"
        ]);

        $bidAmount = $request->get('bid');

        $transformer = new BidTransformer();
        $bidAmount = $transformer->transform($bidAmount);

        // Store the bid
        $success = $this->bids->storeBid($bidAmount, $auctionId, $userId);

        if ( ! $success) {
            return Redirect::back()->withErrors('An error occurred while placing the bid.');
        }

        return Redirect::back();
    }
}
