<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Repositories\BidRepository;
use App\Transformers\Bids\BidIndexTransformer;
use App\Transformers\Bids\BidStoreTransformer;
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
     * Display a listing of all the bids placed on an auction.
     *
     * @param $id
     * @return Response
     */
    public function index($id)
    {
        $auctionId = $id;

        // Validate the auction ID
        if (!$this->auctions->isValidAuctionId($auctionId)) {
            App::abort(404, 'Auction not found.');
        };

        // Get the auction title
        $auctionTitle = $this->auctions->getAuctionTitle($auctionId);

        // Get the bids
        $bids = $this->bids->getBidsForAuction($auctionId);

        // Apply pagination to the data
        list($paginator, $bids) = $this->preparePaginator($bids, $perPage = 10);

        // Transform the data
        $transformer = App::make(BidIndexTransformer::class);
        $bids = $transformer->transformMany($bids);

        // Render the page
        return view('auctions.bids.index')
            ->with(compact('id', 'auctionTitle', 'bids', 'paginator'));
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
        if (!$this->auctions->isValidAuctionId($auctionId)) {
            App::abort(404, 'Auction not found.');
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

        $transformer = new BidStoreTransformer();
        $bidAmount = $transformer->transform($bidAmount);

        // Store the bid
        $success = $this->bids->storeBid($bidAmount, $auctionId, $userId);

        if ( ! $success) {
            return Redirect::back()->withErrors('An error occurred while placing the bid.');
        }

        return Redirect::back();
    }
}
