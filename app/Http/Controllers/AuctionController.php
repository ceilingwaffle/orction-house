<?php

namespace App\Http\Controllers;

use App\Repositories\AuctionRepository;
use App\Services\PaginationService;
use App\Transformers\AuctionsIndexTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class AuctionController extends Controller
{
    /**
     * @var AuctionRepository
     */
    private $auctions;

    /**
     * @var PaginationService
     */
    private $paginator;

    public function __construct
    (
        AuctionRepository $auctions,
        PaginationService $paginator
    ) {
        $this->auctions = $auctions;
        $this->paginator = $paginator;
    }

    /**
     * Render the auctions index page
     *
     * @return $this
     */
    public function getIndex()
    {
        $auctions = $this->auctions->getAuctions();

        // Transform the data so we can present it differently
        $transformer = new AuctionsIndexTransformer();
        $auctions = $transformer->transformCollection($auctions);

        // Apply pagination to the data
        $perPage = 4;
        $currentPage = \Input::get('page', 1);
        $paginator = $this->paginator->makePaginated($auctions, $perPage, $currentPage);
        $auctions = $paginator->getPaginatedData();
        $paginator = $paginator->getPaginatorHtml();

        return view('auctions.index')->with(compact('auctions', 'paginator'));
    }

}
