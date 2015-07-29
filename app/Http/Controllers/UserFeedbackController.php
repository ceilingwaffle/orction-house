<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Repositories\FeedbackRepository;
use App\Services\PaginationService;
use App\Transformers\Feedback\FeedbackIndexTransformer;
use App\Transformers\Feedback\FeedbackStoreTransformer;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
use Session;

class UserFeedbackController extends Controller
{
    /**
     * @var FeedbackRepository
     */
    private $feedback;

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
        FeedbackRepository $feedback,
        AuctionRepository $auctions,
        PaginationService $paginator
    )
    {
        $this->feedback = $feedback;
        $this->auctions = $auctions;
        $this->paginator = $paginator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $username
     * @return Response
     */
    public function index(Request $request, $username)
    {
        // Validate the username
        if (!User::isValidUsername($username)) {
            App::abort(404);
        }

        // Get the feedback received by the user
        $feedbackResults = $this->feedback->getFeedbackSentToUser($username);

        // Check if we need to highlight a particular piece of feedback
        $highlightAuctionId = Input::get('highlightAuctionId');
        $itemsPerPage = 6;

        if ($highlightAuctionId) {
            // Get the page number where the highlighted auction will reside
            foreach ($feedbackResults as $i => $feedback) {
                if ($feedback->auction_id == $highlightAuctionId) {
                    // Calculate the page number from the item index
                    $loadPageNum = (int)ceil(($i + 1) / $itemsPerPage);

                    // Reload the page and go to the page number where the highlighted feedback resides
                    return $this->redirectToHighlightRow($loadPageNum, $highlightAuctionId);
                }
            }
        }

        // Apply pagination
        list($paginator, $feedbackPaginated) = $this->preparePaginator($feedbackResults, $itemsPerPage);

        // Transform the data
        $transformer = App::make(FeedbackIndexTransformer::class);
        $feedbackData = $transformer->transformMany($feedbackPaginated);

        // Render the page
        return view('feedback.index')
            ->with(compact(
                'username',
                'feedbackData',
                'paginator'
            ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @return Response
     */
    public function create($id)
    {
        $auctionId = $id;

        $this->runFeedbackPreValidations($auctionId);

        // Get the feedback types
        $feedbackTypes = $this->feedback->getFeedbackTypes();

        // Render the page
        return view('feedback.create')->with(compact('auctionId', 'feedbackTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @param  Request $request
     * @return Response
     */
    public function store($id, Request $request)
    {
        $auctionId = $id;

        $this->runFeedbackPreValidations($auctionId);

        // Validate form data
        $this->validate($request, [
            'rating' => 'required|feedback_type',
            'message' => 'required|max:200'
        ]);

        // Transform the form data
        $transformer = App::make(FeedbackStoreTransformer::class);
        $feedbackData = $transformer->transform([
            'feedback_type_id' => Input::get('rating'),
            'message' => Input::get('message'),
            'auction_id' => $auctionId,
            'left_by_user_id' => Auth::user()->id,
        ]);

        // Store the feedback
        $feedback = $this->feedback->createFeedback($feedbackData);

        if (!$feedback) {
            return Redirect::back()->withErrors('Sorry, something went wrong trying to save your feedback.');
        }

        // Redirect to the feedback page
        $sellerUsername = $this->auctions->getAuctionSellerUsername($auctionId);

        return Redirect::to("/users/{$sellerUsername}/feedback?highlightAuctionId={$auctionId}");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Sets a session variable (to be used to highlight a feedback row), then returns
     * a Redirect object to redirect to the page where the highlighted feedback resides
     *
     * @param $loadPageNum
     * @param $highlightAuctionId
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToHighlightRow($loadPageNum, $highlightAuctionId)
    {
        // Set the paginator page number and remove the highlight auction ID from the URL query
        $query = $_GET;
        $query['page'] = $loadPageNum;
        unset($query['highlightAuctionId']);
        $query_result = http_build_query($query);

        // Save the highlighted auction ID in the session
        Session::flash('highlightAuction', $highlightAuctionId);

        // Rebuild the URI
        $uri = strtok($_SERVER["REQUEST_URI"], '?') . '/?' . $query_result;

        // Redirect to the new URI
        return Redirect::to($uri);
    }

    /**
     * @param $auctionId
     */
    protected function runFeedbackPreValidations($auctionId)
    {
        // Validate the auction ID
        if (!$this->auctions->isValidAuctionId($auctionId)) {
            App::abort(404, 'Auction not found.');
        }

        // Validate that the auction has ended
        if (!$this->auctions->auctionHasEnded($auctionId)) {
            App::abort(403, 'Auction has ended.');
        }

        // Validate that the user is the winner of the auction
        if (!$this->auctions->userIsAuctionWinner(Auth::user()->id, $auctionId)) {
            App::abort(401, 'User is not auction winner.');
        }

        // Validate that this auction has no feedback assigned to it
        if ($this->feedback->auctionHasFeedback($auctionId)) {
            App::abort(403, 'Auction already has feedback.');
        }
    }
}
