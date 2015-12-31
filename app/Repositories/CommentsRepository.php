<?php namespace App\Repositories;

use App\Comment;

class CommentsRepository {

    protected $model = Comment::class;

    public function create(array $data)
    {
        return Comment::create($data);
    }

    public function update($id, array $data)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($data);
        
        return $comment;
    }

}