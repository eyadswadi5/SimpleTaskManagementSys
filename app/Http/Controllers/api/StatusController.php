<?php

namespace App\Http\Controllers\api;

use App\Models\Status;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = Status::all();
        return $this->success([
            "statuses" => $statuses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "label" => "required|string|unique:statuses,label",
            "key" => "required|string|unique:statuses,key",
        ]);

        try {
            $status = Status::create($validated);
            return $this->success([
                "status" => $status
            ]);
        } catch (QueryException $e) {
            return $this->error("failed creating new status.", 500, []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id)
    {
        try {
            $status = Status::findOrFail($id);
            return $this->success([
                "status" => $status
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("status not found", 404, []);
        } catch (QueryException $e) {
            return $this->error("unable to find status", 500, [
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
            "label" => "required|string",
            "key" => "required|string"
        ]);

        try {
            $status = Status::findOrFail($id);
            $status->update($validated);
            return $this->success([
                "status" => $status
            ]);
        } catch (QueryException $e) {
            return $this->error("unable to update status", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("status not found", 404, []);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $status = Status::findOrFail($id);
            $status->delete();
            return $this->success();
        } catch (QueryException $e) {
            return $this->error("unable to delete status", 500, [
                "errors" => [
                    "database-error" => $e->getMessage()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->error("status not found", 404, []);
        }
    }
}
