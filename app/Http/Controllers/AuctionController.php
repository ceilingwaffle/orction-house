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
use Input;

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
        // Get filter parameters
        $params['title'] = Input::get('title');
        $params['category'] = Input::get('category');
        $params['min_price'] = Input::get('min_price');
        $params['max_price'] = Input::get('max_price');
        $params['order_by'] = Input::get('order_by');
        $params['order_direction'] = Input::get('order_direction');

        // Validate input


        // Fetch the auction data
        $auctions = $this->auctions
            ->prepareQueryFilters($params)
            ->orderBy($params['order_by'], $params['order_direction'])
            ->getAuctions();

        // Apply pagination to the data
        $perPage = 4;
        $currentPage = Input::get('page', 1);
        $paginator = $this->paginator->makePaginated($auctions, $perPage, $currentPage);
        $auctions = $paginator->getPaginatedData();
        $paginator = $paginator->getPaginatorHtml();

        // Transform the data so we can present each value in a custom way
        $transformer = new AuctionsIndexTransformer();
        $auctions = $transformer->transformMany($auctions);

        // Get auction categories
        $categories = $this->auctions->getAuctionCategories();

        // Get a list of fields to sort the results by
        $sortableFields = $this->auctions->getAuctionSortableFields();

        // Render the page
        return view('auctions.index')
                ->with(compact(
                    'auctions',
                    'paginator',
                    'categories',
                    'sortableFields'
                ));
    }

}
