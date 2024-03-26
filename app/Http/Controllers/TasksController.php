<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, User $user = null)
    {
        $advertiseId = $request->input('advertise');
        $userId = !empty($user) ? $user->id : auth()->id();
        $tasks = Task::where('user_id', $userId)->where(function ($query) use ($advertiseId) {
            if (!empty($advertiseId)) {
                $query->whereHas('advertise', function ($query) use ($advertiseId) {
                    $query->where('id', $advertiseId);
                });
            }
        })->with('advertise')->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user = null)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Task::create([
            'user_id' => !empty($user) ? $user->id : auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return Inertia::location(route('tasks'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
