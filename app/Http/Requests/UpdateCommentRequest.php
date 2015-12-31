<?php

namespace App\Http\Requests;

use App\Comment;
use App\Http\Requests\Request;

class UpdateCommentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $commentId = request()->input('id');
        $comment = Comment::findOrFail($commentId);

        return auth()->check() && $comment->isOwner(auth()->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'    =>  'required|exists:comments,id',
            'comment'   =>  'required',
            'event_id'  =>  'required|exists:events,id'
        ];
    }
}
