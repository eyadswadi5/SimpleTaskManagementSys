<?php

namespace App\Http\Controllers\api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);
            $comments = $task->comments;
            $comments = $comments->map(function ($comment) use ($task) {
                return [
                    "content" => $comment->content,
                    "user" => $comment->writer,
                    "task" => $task,
                ];
            });
    
            return $this->success([
                "comments" => $comments,
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("can't get comments, task not found.");
        } catch (QueryException $e) {
            return $this->error("unable to get comments", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $task_id)
    {
        $validated = $request->validate([
            "user_id" => "required|int|exists:users,id",
            "content" => "required|string",
        ]);

        try {
            $task = Task::findOrFail($task_id);
            $comment = Comment::create([
                "task_id" => $task->id,
                "user_id" => $request->user_id,
                "content" => $request->content
            ]);
            return $this->success([
                "comment" => $comment
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("can't add comment, task not found.");
        } catch (QueryException $e) {
            return $this->error("unable to add comment", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            return $this->success([
                "comment" => $comment
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("comment not found", 404, []);
        } catch (QueryException $e) {
            return $this->error("unable to find comment", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            "content" => "required|string",
        ]);

        try {
            $comment = Comment::findOrFail($id);
            $comment->update($validated);

            return $this->success([
                "comment" => $comment
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("can't update comment.");
        } catch (QueryException $e) {
            return $this->error("unable to update comment", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return $this->success();
        } catch (QueryException $e) {
            return $this->error("unable to delete comment", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("comment not found", 404, []);
        }
    }
}
