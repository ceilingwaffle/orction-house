<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Services\PaginationService;
use App\Transformers\Auctions\AuctionIndexTransformer;
use App\Transformers\Auctions\AuctionViewTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
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
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $params['title'] = Input::get('title');
        $params['category'] = Input::get('category');
        $params['min_price'] = Input::get('min_price');
        $params['max_price'] = Input::get('max_price');
        $params['order_by'] = Input::get('order_by');
        $params['order_direction'] = Input::get('order_direction');

        // Validate input. Redirects back with errors if any validation fails.
        $this->validate($request, [
            'title' => 'max:50',
            'category' => 'numeric|auction_category',
            'min_price' => 'money',
            'max_price' => 'money',
            'order_by' => 'auction_order_by',
            'order_direction' => 'sort_direction',
        ]);

        // Transform some of the URL parameter values into different values
        $transformer = App::make(AuctionIndexTransformer::class);
        $params = $transformer->transformSearchParams($params);

        // Fetch the auction data
        $auctions = $this->auctions
            ->orderBy($params['order_by'], $params['order_direction'], $this->auctions->getAuctionSortableFields())
            ->getAuctions($params);

        // Apply pagination to the data
        $perPage = 4;
        $currentPage = Input::get('page', 1);
        $paginator = $this->paginator->makePaginated($auctions, $perPage, $currentPage);
        $auctions = $paginator->getPaginatedData();
        $paginator = $paginator->getPaginatorHtml();

        // Transform the auctions data
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

    /**
     * Render the auction view page
     *
     * @param $id
     * @return $this
     */
    public function show($id)
    {
        // Validate the ID
        // todo

        // Fetch the auction data
        $auction = $this->auctions->getAuctionViewData($id);

        // Transform the data
        $transformer = App::make(AuctionViewTransformer::class);
        $auction = $transformer->transform($auction);

        // Render the page
        return view('auctions.view')->with(compact('id', 'auction'));
    }

}
