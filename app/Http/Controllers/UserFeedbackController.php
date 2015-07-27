<?php

namespace App\Http\Controllers;

use App;
use App\Repositories\AuctionRepository;
use App\Repositories\FeedbackRepository;
use App\Services\PaginationService;
use App\Transformers\Feedback\FeedbackIndexTransformer;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
    ) {
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
        if ( ! User::isValidUsername($username)) {
            App::abort(404);
        }

        // Get the feedback received by the user
        $feedbackResults = $this->feedback->getFeedbackSentToUser($username);

        // Transform the data
        $transformer = new FeedbackIndexTransformer();
        $transformedFeedback = $transformer->transformMany($feedbackResults);

        // Apply pagination
        list($paginator, $feedbackData) = $this->preparePaginator($transformedFeedback, $perPage = 6);

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

        // Validate the auction ID
        if ( ! $this->auctions->isValidAuctionId($auctionId)) {
            App::abort(404, 'Auction not found.');
        }

        // Get the feedback types
        $feedbackTypes = $this->feedback->getFeedbackTypes();

        // Render the page
        return view('feedback.create')->with(compact('auctionId', 'feedbackTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
