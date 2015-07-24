<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Services\ListAuctionService;
use App\Services\PaginationService;
use App\Transformers\Auctions\AuctionIndexTransformer;
use App\Transformers\Auctions\AuctionViewTransformer;
use Auth;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Redirect;
use Response;

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
     * Renders the auctions index page
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

        // Validate the search filter input. Redirects back with errors if any validation fails.
        $this->validate($request, [
            'title' => 'max:50',
            'category' => 'integer|min:1|auction_category',
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
     * Renders the auction view page
     *
     * @param $id
     * @return $this
     */
    public function show($id)
    {
        // Validate the ID
        $valid = $this->auctions->isValidAuctionId($id);

        if (!$valid) {
            App::abort(404, "Auction not found.");
        }

        // Fetch the auction data
        $auction = $this->auctions->getAuctionViewData($id);

        // Transform the data
        $transformer = App::make(AuctionViewTransformer::class);
        $auction = $transformer->transform($auction);

        // Render the page
        return view('auctions.view')->with(compact('id', 'auction'));
    }

    /**
     * Renders the auction create page
     *
     * @return $this
     */
    public function create()
    {
        $categories = $this->auctions->getAuctionCategories();
        $conditions = $this->auctions->getAuctionConditions();

        return view('auctions.create')->with(compact('categories', 'conditions'));
    }

    /**
     * Stores a new auction
     *
     * @param Request $request
     * @return $this
     */
    public function store(Request $request)
    {
        // Validate the form input. Redirect back if any errors.
        $this->validate($request, [
            'item_name' => 'required|max:50',
            'description' => 'required|max:1000',
            'category' => 'required|integer|min:1|auction_category',
            'condition' => 'required|integer|min:1|auction_condition',
            'starting_price' => 'required|money',
            'days' => 'required|integer|min:1|max:14',
            'photo' => 'image|max:1000', // max file size 1,000 kb
        ]);

        $auctionCreator = App::make(ListAuctionService::class);

        // Prepare the photo for storage
        if ($request->file('photo')) {
            try {
                $photoFileName = $auctionCreator->preparePhoto($request->file('photo'));
            } catch (Exception $e) {
                return Redirect::back()->withErrors('An error occurred while processing the photo. Please try again later.');
            }
        }

        // Create the auction
        $auction = $auctionCreator->createAuction([
            'title' => Input::get('item_name'),
            'description' => Input::get('description'),
            'category_id' => Input::get('category'),
            'condition_id' => Input::get('condition'),
            'start_price' => Input::get('starting_price'),
            'future_days_to_end' => Input::get('days'),
            'photo_file' => isset($photoFileName) ? $photoFileName : null,
            'user_id' => Auth::user()->id,
        ]);

        // Redirect back with an error message if the auction was not created
        if (!$auction) {
            return Redirect::back()->withErrors('Sorry, something went wrong trying to save your auction.');
        }

        // The auction was saved successfully. Show the auction page.
        return Redirect::to('/auctions/' . $auction->id);
    }

}
