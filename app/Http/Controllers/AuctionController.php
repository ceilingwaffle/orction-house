<?php

namespace App\Http\Controllers;

use App\Repositories\AuctionRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AuctionController extends Controller
{
    /**
     * @var AuctionRepository
     */
    private $auctions;

    public function __construct(AuctionRepository $auctions)
    {
        $this->auctions = $auctions;
    }

    public function getIndex()
    {
        $auctions = $this->auctions->getAuctions();

        return view('auctions.index')->with(compact('auctions'));
    }
}
