<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Services\ListAuctionService;
use App\Transformers\Auctions\AuctionEditTransformer;
use App\Transformers\Auctions\AuctionIndexTransformer;
use App\Transformers\Auctions\AuctionViewTransformer;
use App\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Redirect;

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
            ->orderBy($params['order_by'], $params['order_direction'],
                $this->auctions->getAuctionSortableFields())
            ->getAuctions($params);

        // Apply pagination to the data
        list($paginator, $auctions) = $this->preparePaginator($auctions);

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
        $this->validateAuctionId($id);

        // Fetch the auction data
        $auction = $this->auctions->getAuctionViewData($id);

        // Transform the data
        $transformer = App::make(AuctionViewTransformer::class);
        $auction = $transformer->transform($auction);

        $userCanUpdate = $this->userCanUpdateAuction(Auth::user(), $id, $auction['auction_seller_username']);

        // Render the page
        return view('auctions.view')
            ->with(compact('id', 'auction', 'userCanUpdate'));
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

        return view('auctions.create')
            ->with(compact('categories', 'conditions'));
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
            'date_ending' => "required|date_format:d/m/Y|after:tomorrow|before:+15 days",
            'photo' => 'image|max:1000', // max file size 1,000 kb
        ], [
            'date_ending.date_format' => 'The auction end date is not a valid date (format must be DD/MM/YYYY and 1 to 14 days from now).',
            'date_ending.after' => 'The auction end date must be between 1 to 14 days from now.',
            'date_ending.before' => 'The auction end date must be between 1 to 14 days from now.',
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
            'date_ending' => Input::get('date_ending'),
            'photo_file' => isset($photoFileName) ? $photoFileName : null,
            'user_id' => Auth::user()->id,
        ]);

        // Redirect back with an error message if the auction was not created
        if (!$auction) {
            return Redirect::back()->withErrors('Sorry, something went wrong trying to save your auction.');
        }

        // The auction was saved successfully. Redirect to the auction
        return Redirect::to('/auctions/' . $auction->id);
    }

    /**
     * Shows a form for updating an existing auction
     *
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $this->validateAuctionId($id);

        // Validate auction has not ended
        if ($this->auctions->auctionHasEnded($id)) {
            App::abort(403, 'Sorry, this auction has ended and cannot be updated.');
        }

        // Validate auction owned by user
        if (!$this->auctions->isAuctionOwner($id, Auth::user()->id)) {
            App::abort(401, 'Sorry, you cannot update this auction because you
                                are not logged in as the user who created it.');
        }

        // Get data for the form
        $categories = $this->auctions->getAuctionCategories();
        $conditions = $this->auctions->getAuctionConditions();

        // Fetch the auction data and transform it
        $auction = $this->auctions->getAuctionEditFormData($id);
        $transformer = App::make(AuctionEditTransformer::class);
        $auction = $transformer->transform($auction);

        // Render the page
        return view('auctions.edit')
            ->with(compact('id', 'auction', 'categories', 'conditions'));
    }

    /**
     * Updates an auction in storage
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $this->validateAuctionId($id);

        // Validate auction has not ended
        if ($this->auctions->auctionHasEnded($id)) {
            return Redirect::back()
                ->withErrors('Sorry, this auction has ended and cannot be updated.');
        }

        // Validate auction owned by user
        if (!$this->auctions->isAuctionOwner($id, Auth::user()->id)) {
            return Redirect::back()
                ->withErrors('Sorry, you cannot update this auction because you
                                are not logged in as the user who created it.');
        }

        // Validate the form input. Redirect back if any errors.
        $this->validate($request, [
            'item_name' => 'required|max:50',
            'description' => 'required|max:1000',
            'category' => 'required|integer|min:1|auction_category',
            'condition' => 'required|integer|min:1|auction_condition',
            //'starting_price' => 'required|money',
            //'date_ending' => "required|date_format:d/m/Y|after:tomorrow|before:+15 days",
            'date_ending' => "date_format:d/m/Y",
            'photo' => 'image|max:1000', // max file size 1,000 kb
            'delete_existing_photo' => 'boolean',
        ], [
            'date_ending.date_format' => 'The auction end date is not a valid date (format must be DD/MM/YYYY and 1 to 14 days from now).',
            'date_ending.after' => 'The auction end date must be between 1 to 14 days from now.',
            'date_ending.before' => 'The auction end date must be between 1 to 14 days from now.',
        ]);


        // Validate that if the auction ending date input is a different date than the existing date,
        // the auction date must not be not ending today, and must be set 1 to 14 days in the future.
        $patchEndDate = $this->shouldPatchValidEndDate($id, Input::get('date_ending'));

        if ($patchEndDate instanceOf RedirectResponse) {
            return $patchEndDate;
        }

        // Update the auction
        $auctionCreator = App::make(ListAuctionService::class);

        // todo: delete the old photo if one exists
        // todo: refactor to make update() and store() more DRY (currently we have duplicate auction creator code)

        // Prepare the photo for storage
        if ($request->file('photo')) {
            try {
                $photoFileName = $auctionCreator->preparePhoto($request->file('photo'));
            } catch (Exception $e) {
                return Redirect::back()->withErrors('An error occurred while processing the photo. Please try again later.');
            }
        }

        // Set the auction data to be updated
        $updateData = [
            'title' => Input::get('item_name'),
            'description' => Input::get('description'),
            'category_id' => Input::get('category'),
            'condition_id' => Input::get('condition'),
            'photo_file' => isset($photoFileName) ? $photoFileName : null,
            'delete_existing_photo' => Input::get('delete_existing_photo'),
        ];

        if ($patchEndDate) {
            $updateData['date_ending'] = Input::get('date_ending');
        }

        // Update the auction
        $auction = $auctionCreator->updateAuction($id, $updateData);

        // Redirect to the auction page
        return Redirect::to('/auctions/' . $auction->id);
    }

    /**
     * Returns a 404 error if the auction ID does not exist
     *
     * @param $id
     */
    protected function validateAuctionId($id)
    {
        $valid = $this->auctions->isValidAuctionId($id);

        if (!$valid) {
            App::abort(404, "Auction not found.");
        }
    }

    /**
     * Returns true if the auction end date is valida and should be updated.
     * Redirects back with errors if the date is invalid.
     *
     * @param $auctionId
     * @param $newDateInput
     * @return $this|bool
     */
    protected function shouldPatchValidEndDate($auctionId, $newDateInput)
    {
        $redirectUrl = "/auctions/{$auctionId}/edit";

        // if date input is set
        if (!empty($newDateInput)) {
            // if date = existing date
            $newDate = Carbon::createFromFormat('d/m/Y', $newDateInput);
            $currentAuctionDate = $this->auctions->getAuctionEndDate($auctionId); //dt string
            $currentAuctionDate = Carbon::createFromTimestamp(strtotime($currentAuctionDate));
            if ($newDate->diffInDays($currentAuctionDate) === 0) {
                // continue with update, but do not patch date
                return false;
            } // else if date is not set within 1-14 days
            elseif (!$newDate->between(Carbon::tomorrow()->startOfDay(), Carbon::now()->addDays(15)->startOfDay())) {
                // redirect back with error
                return Redirect::to($redirectUrl)->withErrors('End date must be set within 1 to 14 days from today.');
            } // else (date different to existing date, and set within 1-14 days)
            else {
                // if auction ending today
                if ($currentAuctionDate->isToday()) {
                    // redirect back with error
                    return Redirect::to($redirectUrl)->withErrors('Cannot change the auction end date because the auction is due to end today.');
                } // else (auction not ending today)
                else {
                    // continue with update, and patch the date
                    return true;
                }
            }
        } // if date input is NOT set
        else {
            // continue with update, but do not patch date
            return false;
        }
    }

    /**
     * Returns true if the user is allowed to update this auction
     *
     * @param User $user
     * @param $auctionId
     * @param $auctionSellerUsername
     * @return bool
     */
    protected function userCanUpdateAuction(User $user, $auctionId, $auctionSellerUsername)
    {
        $userIsCreator = $user->username === $auctionSellerUsername;

        $auctionHasNotEnded = !$this->auctions->auctionHasEnded($auctionId);

        return ($userIsCreator === true and $auctionHasNotEnded === true);
    }
}
