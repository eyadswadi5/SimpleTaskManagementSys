<?php

namespace App\Http\Controllers\api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use PriorityEnum;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with("assignee")->with("creator")->get();
        // $tasks = $tasks->map(function ($task) {
        //     return [
        //         "title" => $task->title,
        //         "description" => $task->description,
        //         "status" => $task->status,
        //         "created_by" => $task->creator,
        //         "assigned_to" => $task->assignee,
        //         "finished_at" => $task->finished_at,
        //         "priority" => $task->priority,
        //     ];
        // });

        return $this->success([
            "tasks" => $tasks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => "required|string",
            "description" => "required|string",
            "status_id" => "required|integer|exists:statuses,id",
            "created_by" => "required|integer|exists:users,id",
            "assigned_to" => "required|integer|exists:users,id",
            "priority" => ["required", "string", Rule::enum(PriorityEnum::class)]
        ]);

        try {
            $task = Task::create($validated);
            return $this->success([
                "task" => $task
            ]);
        } catch (QueryException $e) {
            return $this->error("failed creating new task.", 500, []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $task = Task::findOrFail($id);
            $taskToShow = [
                    "title" => $task->title,
                    "description" => $task->description,
                    "status" => $task->status,
                    "created_by" => $task->creator,
                    "assigned_to" => $task->assignee,
                    "finished_at" => $task->finished_at,
                    "priority" => $task->priority,
                ];

            return $this->success([
                "task" => $taskToShow
            ]);
        
        } catch (ModelNotFoundException $e) {
            return $this->error("task not found", 404, []);
        } catch (QueryException $e) {
            return $this->error("unable to find task", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        }
    }

    public function filter(Request $request) {
        $validated = $request->validate([
            "title" => "nullable|string",
            "description" => "nullable|string",
            "status_id" => "nullable|integer|exists:statuses,id",
            "created_by" => "nullable|integer|exists:users,id",
            "assigned_to" => "nullable|integer|exists:users,id",
            "priority" => ["nullable", "string", Rule::enum(PriorityEnum::class)]
        ]);

        try {
            $query = Task::query();

            if (isset($validated["title"]))
                $query->where("title", "like", "%". $validated["title"] . "%");

            if (isset($validated["description"]))
                $query->where("description", "like", "%". $validated["description"] . "%");

            if (isset($validated["status_id"]))
                $query->where("status_id", $validated["status_id"]);

            if (isset($validated["created_by"])) 
                $query->where("created_by", $validated["created_by"]);
            
            if (isset($validated["assigned_to"])) 
                $query->where("assigned_to", $validated["assigned_to"]);
            
            if (isset($validated["priority"])) 
                $query->where("priority", $validated["priority"]);
            
            $tasks = $query->get();
            
            return $this->success([
                "tasks" => $tasks
            ]);

        } catch (QueryException $e) {
            return $this->error("unable to get task", 500, [
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
            "title" => "required|string",
            "description" => "required|string",
            "status_id" => "required|integer|exists:statuses,id",
            "created_by" => "required|integer|exists:users,id",
            "assigned_to" => "required|integer|exists:users,id",
            "priority" => ["required", "string", Rule::enum(PriorityEnum::class)]
        ]);

        try {
            $task = Task::findOrFail($id);
            $task->update($validated);
            return $this->success([
                "task" => $task
            ]);
        } catch (QueryException $e) {
            return $this->error("unable to update task", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("task not found", 404, []);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return $this->success();
        } catch (QueryException $e) {
            return $this->error("unable to delete task", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("task not found", 404, []);
        }
    }
}
