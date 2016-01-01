<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentWasCreated;
use App\Events\CommentWasDeleted;
use App\Events\CommentWasUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Repositories\CommentsRepository;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Comments repository
     * 
     * @var App\Repositories\CommentsRepository
     */
    private $repo;

    public function __construct(CommentsRepository $repo)
    {
        $this->middleware('auth');
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $requestData = request()->only(['comment', 'event_id']);

        $comment = $this->repo->create([
            'comment'   =>  $requestData['comment'],
            'event_id'  =>  $requestData['event_id'],
            'user_id'    =>  auth()->user()->id
        ]);
        $comment->load('user');

        event(new CommentWasCreated($comment));

        return $this->respondSuccess(['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Requests\UpdateEventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        $requestData = request()->only(['comment', 'event_id']);

       $comment = $this->repo->update($id, [
           'comment'   =>  $requestData['comment'],
           'event_id'  =>  $requestData['event_id'],
       ]);

       $comment->load('user');

       event(new CommentWasUpdated($comment));

       return $this->respondSuccess(['comment' => $comment]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (!$comment->isOwner(auth()->user())) {
            abort(401);
        }

        $comment->delete($id);
        
        event(new CommentWasDeleted($id));

        return $this->respondSuccess();
    }
}
